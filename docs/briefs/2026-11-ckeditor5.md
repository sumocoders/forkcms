# Brief: CKEditor 4 -> CKEditor 5

Status: not started. Depends on `2026-11-backend-bootstrap5.md` being merged.

## Context

The backend uses CKEditor 4.12.1 from the deprecated `ckeditor` npm package. The open-source CKEditor 4 line ended at
4.22.1; later `ckeditor4` releases need a paid LTS license. The editor gets no more security fixes.

## Current setup

- Copied by `webpack.backend.js` (`ckeditorPlugins`, `ckeditorFiles`) into `src/Backend/Core/Js/ckeditor`.
- Custom committed plugins not copied by webpack: `oembed`, `codemirror`, `medialibrary`, `divarea`, `lineutils`.
- Configured in `src/Backend/Core/Js/backend.js:330-560` (skin `moono-lisa`, `toolbar_Full`, `extraPlugins`,
  jQuery adapter `.ckeditor()`), loaded per form by `src/Backend/Form/Type/EditorType.php:26-27`.
- Content templates: `src/Backend/Core/Layout/EditorTemplates/templates.js`.
- Frontend editor content CSS: `src/Frontend/Themes/Bootstrap/src/Layout/Sass/editor_content.scss`.
- Editors inside Bootstrap modals need the BS5 focus-trap workaround from the Bootstrap 5 branch.
- Stale docs: `src/Backend/Core/Js/README.md` (online builder workflow).

## Goal

Replace CKEditor 4 with CKEditor 5 (GPL build, self-hosted via npm and webpack) and keep all current editing features:
headings/styles, links, images through the MediaLibrary picker, tables, embeds (oembed), source editing, content
templates, paste from Word.

## Open questions

- CKEditor 5 GPL license is fine for Fork CMS (MIT)? Check compatibility, else evaluate TinyMCE (MIT since v7? check)
  or another editor.
- Existing stored HTML: CKEditor 5 normalises markup more strictly (General HTML Support plugin needed for custom
  classes/divs).
- MediaLibrary integration: port the `medialibrary` plugin to a CKEditor 5 plugin or a custom upload adapter.

## Verification

Blog, Pages, Faq, ContentBlocks, FormBuilder (textarea fields) edit + save round trip; editor inside modals; existing
content renders unchanged on the frontend.
