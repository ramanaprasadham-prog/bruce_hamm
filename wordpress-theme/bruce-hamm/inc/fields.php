<?php
if (!defined('ABSPATH')) {
    exit;
}

const BRUCE_HAMM_META_PREFIX = '_bh_';

/* ------------------------------------------------------------------------
 * Looking up the page a request or edit screen belongs to
 * --------------------------------------------------------------------- */

function bruce_hamm_page_key($post_id)
{
    $post_id = (int) $post_id;
    if (!$post_id) {
        return '';
    }

    $schema = bruce_hamm_field_schema();
    if ($post_id === (int) get_option('page_on_front') && isset($schema['home'])) {
        return 'home';
    }

    $post = get_post($post_id);
    if (!$post || 'page' !== $post->post_type) {
        return '';
    }

    return isset($schema[$post->post_name]) ? $post->post_name : '';
}

function bruce_hamm_current_page_id()
{
    $id = get_queried_object_id();
    return $id ? (int) $id : (int) get_the_ID();
}

function bruce_hamm_find_field($page_key, $key)
{
    $schema = bruce_hamm_field_schema();
    if (empty($schema[$page_key])) {
        return null;
    }

    foreach ($schema[$page_key] as $box) {
        foreach ($box['fields'] as $field) {
            if ($field['key'] === $key) {
                return $field;
            }
        }
    }

    return null;
}

function bruce_hamm_field_default($field)
{
    if (isset($field['default'])) {
        return $field['default'];
    }

    return 'repeater' === $field['type'] ? [] : '';
}

/**
 * Value of an editable field. Falls back to the built-in default until someone saves
 * the page, so existing sites keep their current look after the theme is updated.
 */
function bruce_hamm_get($key, $post_id = null)
{
    $post_id = $post_id ? (int) $post_id : bruce_hamm_current_page_id();
    $page_key = bruce_hamm_page_key($post_id);
    $field = $page_key ? bruce_hamm_find_field($page_key, $key) : null;
    if (!$field) {
        return '';
    }

    $meta_key = BRUCE_HAMM_META_PREFIX . $key;
    if (metadata_exists('post', $post_id, $meta_key)) {
        $value = get_post_meta($post_id, $meta_key, true);
        if ('repeater' === $field['type'] && !is_array($value)) {
            return [];
        }
        return $value;
    }

    return bruce_hamm_field_default($field);
}

/* ------------------------------------------------------------------------
 * Sanitising what comes back from the edit screen
 * --------------------------------------------------------------------- */

function bruce_hamm_sanitize_field($field, $raw)
{
    switch ($field['type']) {
        case 'repeater':
            $rows = [];
            if (!is_array($raw)) {
                return $rows;
            }
            foreach ($raw as $row) {
                if (!is_array($row)) {
                    continue;
                }
                $clean = [];
                $has_value = false;
                foreach ($field['fields'] as $sub) {
                    $value = bruce_hamm_sanitize_field($sub, $row[$sub['key']] ?? '');
                    $clean[$sub['key']] = $value;
                    if ('' !== $value && 'select' !== $sub['type'] && 'checkbox' !== $sub['type']) {
                        $has_value = true;
                    }
                }
                if ($has_value) {
                    $rows[] = $clean;
                }
            }
            return $rows;

        case 'image':
        case 'audio':
        case 'url':
            return esc_url_raw(trim((string) $raw));

        case 'textarea':
            return sanitize_textarea_field((string) $raw);

        case 'checkbox':
            return $raw ? '1' : '';

        case 'select':
            $options = isset($field['options']) ? $field['options'] : [];
            return isset($options[$raw]) ? $raw : (string) $field['default'];

        default:
            return sanitize_text_field((string) $raw);
    }
}

/* ------------------------------------------------------------------------
 * Edit-screen boxes
 * --------------------------------------------------------------------- */

function bruce_hamm_register_page_boxes($post)
{
    $page_key = bruce_hamm_page_key($post->ID);
    if (!$page_key) {
        return;
    }

    $schema = bruce_hamm_field_schema();
    foreach ($schema[$page_key] as $box) {
        add_meta_box(
            'bh_' . $box['id'],
            $box['title'],
            'bruce_hamm_render_box',
            'page',
            'normal',
            'high',
            ['box' => $box]
        );
    }
}

add_action('add_meta_boxes_page', 'bruce_hamm_register_page_boxes');

function bruce_hamm_render_box($post, $meta)
{
    $box = $meta['args']['box'];
    wp_nonce_field('bruce_hamm_fields', 'bruce_hamm_fields_nonce');

    if (!empty($box['intro'])) {
        echo '<p class="description">' . esc_html($box['intro']) . '</p>';
    }

    foreach ($box['fields'] as $field) {
        printf('<input type="hidden" name="bh_keys[]" value="%s">', esc_attr($field['key']));
        bruce_hamm_render_field($field, 'bh[' . $field['key'] . ']', bruce_hamm_get($field['key'], $post->ID));
    }
}

function bruce_hamm_media_input($name, $value, $type, $input_id = '', $role = '')
{
    $is_image = 'image' === $type;
    $label = $is_image ? 'Choose photo' : 'Choose audio file';
    ?>
    <div class="bh-media" data-type="<?php echo esc_attr($type); ?>">
      <?php if ($is_image) : ?>
        <div class="bh-media-preview"><?php if ($value) : ?><img src="<?php echo esc_url($value); ?>" alt=""><?php endif; ?></div>
      <?php endif; ?>
      <input type="text" class="widefat bh-media-input" <?php echo $input_id ? 'id="' . esc_attr($input_id) . '"' : ''; ?> name="<?php echo esc_attr($name); ?>" value="<?php echo esc_attr($value); ?>" placeholder="<?php echo $is_image ? 'Image address' : 'Audio file address'; ?>">
      <p class="bh-media-actions">
        <button type="button" class="button bh-media-choose"><?php echo esc_html($label); ?></button>
        <button type="button" class="button-link bh-media-clear">Remove</button>
      </p>
    </div>
    <?php
}

function bruce_hamm_render_field($field, $name, $value)
{
    $type = $field['type'];
    $label = $field['label'];
    $role = isset($field['role']) ? $field['role'] : '';

    if ('repeater' === $type) {
        bruce_hamm_render_repeater($field, $name, is_array($value) ? $value : []);
        return;
    }

    echo '<div class="bh-field bh-field-' . esc_attr($type) . '">';

    if ('checkbox' === $type) {
        printf('<input type="hidden" name="%s" value="">', esc_attr($name));
        printf(
            '<label><input type="checkbox" name="%s" value="1" %s> %s</label>',
            esc_attr($name),
            checked((string) $value, '1', false),
            esc_html($label)
        );
        echo '</div>';
        return;
    }

    echo '<label class="bh-label">' . esc_html($label) . '</label>';

    if ('image' === $type || 'audio' === $type) {
        bruce_hamm_media_input($name, (string) $value, $type);
    } elseif ('textarea' === $type) {
        printf('<textarea class="widefat" rows="3" name="%s">%s</textarea>', esc_attr($name), esc_textarea((string) $value));
    } elseif ('select' === $type) {
        printf('<select name="%s">', esc_attr($name));
        foreach ($field['options'] as $option_value => $option_label) {
            printf(
                '<option value="%s" %s>%s</option>',
                esc_attr($option_value),
                selected((string) $value, (string) $option_value, false),
                esc_html($option_label)
            );
        }
        echo '</select>';
    } else {
        printf(
            '<input type="%s" class="widefat" name="%s" value="%s"%s>',
            'url' === $type ? 'url' : 'text',
            esc_attr($name),
            esc_attr((string) $value),
            $role ? ' data-bh-role="' . esc_attr($role) . '"' : ''
        );
    }

    echo '</div>';
}

function bruce_hamm_render_repeater($field, $name, $rows)
{
    $row_html = function ($index, $row) use ($field, $name) {
        $title = isset($field['item_label'], $row[$field['item_label']]) ? (string) $row[$field['item_label']] : '';
        ?>
        <div class="bh-row">
          <div class="bh-row-head">
            <span class="bh-row-handle dashicons dashicons-menu" title="Drag to reorder"></span>
            <strong class="bh-row-title"><?php echo esc_html(wp_trim_words($title, 10, '…')); ?></strong>
            <span class="bh-row-tools">
              <button type="button" class="button-link bh-row-up" aria-label="Move up">&uarr;</button>
              <button type="button" class="button-link bh-row-down" aria-label="Move down">&darr;</button>
              <button type="button" class="button-link bh-row-remove">Delete</button>
            </span>
          </div>
          <div class="bh-row-body">
            <?php
            foreach ($field['fields'] as $sub) {
                $sub_value = isset($row[$sub['key']]) ? $row[$sub['key']] : (isset($sub['default']) ? $sub['default'] : '');
                bruce_hamm_render_field($sub, $name . '[' . $index . '][' . $sub['key'] . ']', $sub_value);
            }
            ?>
          </div>
        </div>
        <?php
    };

    echo '<div class="bh-repeater" data-title-key="' . esc_attr(isset($field['item_label']) ? $field['item_label'] : '') . '">';
    echo '<p class="bh-label">' . esc_html($field['label']) . '</p>';
    echo '<div class="bh-rows">';
    foreach (array_values($rows) as $index => $row) {
        $row_html($index, is_array($row) ? $row : []);
    }
    echo '</div>';

    echo '<script type="text/html" class="bh-row-template" data-name="' . esc_attr($name) . '">';
    $row_html('__INDEX__', []);
    echo '</script>';

    printf(
        '<p><button type="button" class="button button-secondary bh-row-add">%s</button></p>',
        esc_html(isset($field['add_label']) ? $field['add_label'] : 'Add')
    );
    echo '</div>';
}

/* ------------------------------------------------------------------------
 * Saving
 * --------------------------------------------------------------------- */

function bruce_hamm_save_page_fields($post_id)
{
    if (wp_is_post_revision($post_id) || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)) {
        return;
    }
    if (!isset($_POST['bruce_hamm_fields_nonce']) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['bruce_hamm_fields_nonce'])), 'bruce_hamm_fields')) {
        return;
    }
    if (!current_user_can('edit_page', $post_id)) {
        return;
    }

    $page_key = bruce_hamm_page_key($post_id);
    if (!$page_key) {
        return;
    }

    $submitted = isset($_POST['bh_keys']) && is_array($_POST['bh_keys'])
        ? array_map('sanitize_key', wp_unslash($_POST['bh_keys']))
        : [];
    // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized -- each field is sanitised by type below.
    $data = isset($_POST['bh']) && is_array($_POST['bh']) ? wp_unslash($_POST['bh']) : [];

    $schema = bruce_hamm_field_schema();
    foreach ($schema[$page_key] as $box) {
        foreach ($box['fields'] as $field) {
            if (!in_array(sanitize_key($field['key']), $submitted, true)) {
                continue;
            }
            $raw = isset($data[$field['key']]) ? $data[$field['key']] : ('repeater' === $field['type'] ? [] : '');
            update_post_meta($post_id, BRUCE_HAMM_META_PREFIX . $field['key'], wp_slash(bruce_hamm_sanitize_field($field, $raw)));
        }
    }
}

add_action('save_post_page', 'bruce_hamm_save_page_fields');

/* ------------------------------------------------------------------------
 * Pages whose text lives in boxes rather than the editor hide the editor, so
 * the edit screen only shows things that actually appear on the page.
 * --------------------------------------------------------------------- */

function bruce_hamm_hide_unused_editor()
{
    global $pagenow;
    if (!in_array($pagenow, ['post.php', 'post-new.php'], true)) {
        return;
    }

    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    $post_id = isset($_GET['post']) ? (int) $_GET['post'] : (isset($_POST['post_ID']) ? (int) $_POST['post_ID'] : 0);
    $page_key = bruce_hamm_page_key($post_id);
    if (!$page_key || in_array($page_key, bruce_hamm_pages_with_editor(), true)) {
        return;
    }

    remove_post_type_support('page', 'editor');
}

add_action('admin_init', 'bruce_hamm_hide_unused_editor');

/* ------------------------------------------------------------------------
 * Admin scripts and styles
 * --------------------------------------------------------------------- */

function bruce_hamm_admin_assets($hook)
{
    if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
        return;
    }

    $screen = get_current_screen();
    if (!$screen || !in_array($screen->post_type, ['page', 'bruce_event'], true)) {
        return;
    }

    wp_enqueue_media();
    wp_enqueue_script('jquery-ui-sortable');
    wp_enqueue_style('bruce-hamm-admin', bruce_hamm_asset('admin/fields.css'), [], BRUCE_HAMM_VERSION);
    wp_enqueue_script('bruce-hamm-admin', bruce_hamm_asset('admin/fields.js'), ['jquery', 'jquery-ui-sortable'], BRUCE_HAMM_VERSION, true);
}

add_action('admin_enqueue_scripts', 'bruce_hamm_admin_assets');

function bruce_hamm_block_editor_assets()
{
    $post = get_post();
    if (!$post) {
        return;
    }
    $is_event = 'bruce_event' === $post->post_type;
    if (!$is_event && !('page' === $post->post_type && bruce_hamm_page_key($post->ID))) {
        return;
    }

    wp_enqueue_script(
        'bruce-hamm-block-editor',
        bruce_hamm_asset('admin/block-editor.js'),
        ['wp-data', 'wp-dom-ready', 'wp-preferences'],
        BRUCE_HAMM_VERSION,
        true
    );
}

add_action('enqueue_block_editor_assets', 'bruce_hamm_block_editor_assets');
