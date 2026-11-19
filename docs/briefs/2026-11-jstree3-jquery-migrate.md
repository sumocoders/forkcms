# Brief: jstree 0.9.9a -> jstree 3, jquery-migrate 1.4 -> 3.x

Status: not started. Depends on `2026-11-backend-bootstrap5.md` being merged.

## Context

The backend runs jQuery 3.7.1 with jquery-migrate 1.4.1, a version meant for jQuery 1.9 to 2.x. It restores APIs that
jQuery 3 removed. The main consumer is jstree 0.9.9a (2009), committed in `src/Backend/Modules/Pages/Js/jstree/`. It uses
`$.browser` (5x), `.live` (6x), `.die`, `.andSelf` (3x), `.size()` (83x).

## Current usage

- jstree: Pages tree (`src/Backend/Modules/Pages/Js/Pages.js:1491`) and MediaLibrary folders
  (`src/Backend/Modules/MediaLibrary/Js/MediaLibrary.js:189`), loaded with `jquery.tree.js`, `jquery.cookie.js`,
  `jquery.tree.cookie.js` from Pages `Index/Add/Edit` and MediaLibrary `MediaItemIndex/MediaItemEdit/MediaItemUpload/
  MediaBrowser` actions. Styling in `src/Backend/Core/Layout/Sass/components/_tree.scss`.
- Other code relying on migrate:
  - `.live()` 3x in `src/Backend/Core/Js/jquery/jquery.backend.js:157,616,1081`
  - deprecated but still working in jQuery 3, to clean up in the same pass: `$.parseJSON` (4x), `$.trim` (4x,
    MediaLibraryHelper.js), `.bind`/`.unbind` (31x), `.attr('checked'|'disabled')` that should be `.prop()` (25x)
- The installer loads jquery-migrate too (`templates/Installer/base.html.twig`) but does not need it.

## Goal

1. Replace jstree 0.9.9a with jstree 3.x from npm (copied via `webpack.backend.js`), keeping: drag-and-drop page
   moves (AJAX `move` call), open/closed state persistence (cookie or jstree `state` plugin), context of the selected
   page, MediaLibrary folder selection.
2. Fix the remaining migrate-dependent calls, upgrade to jquery-migrate 3.x, run with migrate warnings on until the
   console is clean, then drop jquery-migrate entirely if nothing is left.

## Verification

Pages: open/close nodes, reload keeps state, drag page to new parent and position, add/edit page highlights in tree.
MediaLibrary: folder tree navigation, move items between folders. Console free of migrate warnings.
