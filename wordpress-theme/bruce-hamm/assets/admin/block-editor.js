/*
 * The page-specific boxes (photos, tracks, videos...) live in the block editor's
 * "Meta Boxes" panel, which WordPress keeps collapsed. Open it the first time each
 * browser loads a page that has such boxes.
 */
(function (wp) {
  if (!wp || !wp.data) return;

  var STORAGE_KEY = 'bruceHammMetaBoxesShown';
  var shown = false;
  try {
    shown = window.localStorage.getItem(STORAGE_KEY) === '1';
  } catch (e) {}

  wp.domReady(function () {
    if (!shown) {
      var prefs = wp.data.dispatch('core/preferences');
      if (prefs && prefs.set) {
        prefs.set('core/edit-post', 'metaBoxesMainIsOpen', true);
        prefs.set('core/edit-post', 'metaBoxesMainOpenHeight', 360);
      }
      try {
        window.localStorage.setItem(STORAGE_KEY, '1');
      } catch (e) {}
    }
  });
})(window.wp);
