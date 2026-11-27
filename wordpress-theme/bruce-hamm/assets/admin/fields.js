(function ($) {
  'use strict';

  function rowTitle($row) {
    var key = $row.closest('.bh-repeater').data('title-key');
    var $input = key ? $row.find('[name$="[' + key + ']"]').first() : $();
    var text = $.trim($input.val() || '');
    if (!text && $input.hasClass('bh-media-input')) text = '';
    $row.find('.bh-row-title').first().text(text.length > 60 ? text.slice(0, 60) + '…' : text);
  }

  function renumber($repeater) {
    $repeater.find('> .bh-rows > .bh-row').each(function (index) {
      $(this)
        .find('[name]')
        .each(function () {
          var name = $(this).attr('name');
          $(this).attr('name', name.replace(/\[(?:\d+|__INDEX__)\](?=\[[^\]]+\]$)/, '[' + index + ']'));
        });
    });
  }

  function initSortable($scope) {
    $scope.find('.bh-rows').sortable({
      handle: '.bh-row-handle',
      items: '> .bh-row',
      axis: 'y',
      update: function () {
        renumber($(this).closest('.bh-repeater'));
      },
    });
  }

  function setPreview($media, url) {
    var $preview = $media.find('.bh-media-preview');
    if (!$preview.length) return;
    $preview.empty();
    if (url) $('<img>').attr({ src: url, alt: '' }).appendTo($preview);
  }

  $(function () {
    initSortable($(document));

    $(document).on('click', '.bh-row-head', function (event) {
      if ($(event.target).closest('.bh-row-tools, .bh-row-handle').length) return;
      $(this).closest('.bh-row').toggleClass('is-open');
    });

    $(document).on('click', '.bh-row-add', function () {
      var $repeater = $(this).closest('.bh-repeater');
      var html = $repeater.find('> .bh-row-template').html();
      var $rows = $repeater.find('> .bh-rows');
      var $row = $($.parseHTML($.trim(html.replace(/__INDEX__/g, String($rows.children().length)))));
      $row.addClass('is-open');
      $rows.append($row);
      renumber($repeater);
      $row.find('input[type="text"], input[type="url"], textarea').first().trigger('focus');
    });

    $(document).on('click', '.bh-row-remove', function () {
      var $repeater = $(this).closest('.bh-repeater');
      $(this).closest('.bh-row').remove();
      renumber($repeater);
    });

    $(document).on('click', '.bh-row-up, .bh-row-down', function () {
      var $row = $(this).closest('.bh-row');
      var $repeater = $row.closest('.bh-repeater');
      if ($(this).hasClass('bh-row-up')) $row.prev('.bh-row').before($row);
      else $row.next('.bh-row').after($row);
      renumber($repeater);
    });

    $(document).on('input change', '.bh-row input, .bh-row textarea', function () {
      rowTitle($(this).closest('.bh-row'));
    });

    $(document).on('input change', '.bh-media-input', function () {
      setPreview($(this).closest('.bh-media'), $(this).val());
    });

    $(document).on('click', '.bh-media-clear', function () {
      var $media = $(this).closest('.bh-media');
      $media.find('.bh-media-input').val('').trigger('change');
    });

    $(document).on('click', '.bh-media-choose', function (event) {
      event.preventDefault();
      var $media = $(this).closest('.bh-media');
      var type = $media.data('type');
      var frame = wp.media({
        title: type === 'image' ? 'Choose a photo' : 'Choose an audio file',
        button: { text: 'Use this ' + (type === 'image' ? 'photo' : 'file') },
        library: { type: type },
        multiple: false,
      });

      frame.on('select', function () {
        var attachment = frame.state().get('selection').first().toJSON();
        $media.find('.bh-media-input').val(attachment.url).trigger('change');

        if (type === 'image') {
          var $scope = $media.closest('.bh-row, .inside');
          var $alt = $scope.find('[data-bh-role="alt"]').first();
          if ($alt.length && !$.trim($alt.val())) {
            $alt.val(attachment.alt || attachment.title || '').trigger('change');
          }
        } else {
          var $title = $media.closest('.bh-row').find('[name$="[title]"]').first();
          if ($title.length && !$.trim($title.val())) {
            $title.val(attachment.title || '').trigger('change');
          }
        }
      });

      frame.open();
    });
  });
})(jQuery);
