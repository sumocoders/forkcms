# Brief: backend Bootstrap 3 -> Bootstrap 5

## Context

The backend (`src/Backend`) and the installer (`templates/Installer`, which loads the backend `screen.css`) still run on
Bootstrap 3 (`bootstrap-sass` 3.4.1, BS3 jQuery plugins, BS3-only add-ons, Font Awesome 4). BS3 is EOL and blocks
cleanup of several deprecated npm packages. Goal: move the backend + installer to Bootstrap 5.3 (already installed as the
`bootstrap5` npm alias, used by the active `Bootstrap` frontend theme) and drop BS3 completely.

Decisions taken with the user:
- Layout (structure, navigation placement, page regions) stays the same. Styling may change; default BS5 look is fine,
  keep the Fork palette (navy `#01417b` sidebar, primary `#2F77CF`, Open Sans) mapped onto BS5 variables.
- All current functionality stays.
- The legacy `Fork` frontend theme is unused: remove it (so `bootstrap-sass`, FA4 and the accessibility plugin can go).
- CKEditor 4 and jstree 0.9.9a stay in this task (kept working inside BS5). Separate follow-up briefs for both.
- Plugin replacements ("lean set"): tagsinput + typeahead -> Tom Select; drop bootstrap-accessibility-plugin; FA4 -> FA6;
  datepickers -> native `<input type="date">`; jQuery `cropper` -> `cropperjs` 1.x; `jquery-ui-dist` 1.12 -> `jquery-ui` 1.14.

Audit summary (what makes this big):
- Backend Sass is custom on top of BS3 variables, zero BS3 mixins. 16 media queries map 1:1 to `media-breakpoint-up()`.
  Breaking partials: `_panels` (161 l), `_navbar` (540 l), `_tabs`, `_forms`, `_buttons`, `_labels`/`_tags`/`_locale`,
  `_alerts` (`.close`), `_authentication`, `_installer`.
- Markup: `FormLayout.html.twig` extends `bootstrap_3_layout.html.twig`; ~830 `panel` hits / 72 files, ~400 `col-*`,
  ~380 `form-group`, ~290 `control-label`, ~160 `btn-default`, ~146 `pull-*`, ~138 `data-toggle`, ~78 `help-block`.
  PHP emits markup too (`DataGridFunctions.php:216` tooltip, 11 mass-action `data-target` options).
- JS: BS5 keeps the jQuery plugin bridge, so 46 `.modal()`, 6 `.tab()`, 5 `.tooltip()` calls and 21 `.bs.*` listeners
  keep working. Must fix: `backend.js:554` `enforceFocus` override (CKEditor in modals), `data-*` attrs built in JS
  strings, class names in JS strings (Pages.js 32x `panel`), `MediaLibrary.js:88` `.modal('close')` (bug today).

## Approach

Follow-up work lives in separate briefs: `docs/briefs/2026-11-ckeditor5.md` and
`docs/briefs/2026-11-jstree3-jquery-migrate.md`. One branch `feat/backend-bootstrap5` from `master`, one commit per
phase below, each commit only after the user validated it. Rebuild assets
(`npm run build`) in each phase that touches Sass/vendor files and commit the built output, as the repo does today.

### Phase 1: remove the Fork theme and BS3 frontend leftovers (`chore:`)
- Delete `src/Frontend/Themes/Fork/`.
- Installer: `src/Backend/Modules/Extensions/Installer/Installer.php` `configureFrontendForkTheme()` installs `Fork`
  templates + sets `Core.theme`. Switch to installing the `Bootstrap` theme templates (paths from its `info.xml`) and
  `Core.theme = Bootstrap`. Same for fallback in `src/Backend/Core/Installer/ModuleInstaller.php:286`.
- Replace the `'Fork'` default in every `->get('Core', 'theme', 'Fork')` (~25 call sites: Frontend `TwigTemplate`,
  `Page`, `Rss`, Common `BaseTwigTemplate`, `PageContextDataCollector`, Backend Extensions/Pages/ContentBlocks/FormBuilder)
  with `'Bootstrap'`.
- Tests: `DetailThemeTest.php`, `Blog/Tests/Actions/EditTest.php` (`theme=Fork`), `ModulesSettingsTest.php`; drop the
  `Fork` rows from `tests/data/test_db.sql` (`themes_templates` ids 3/4) and check `Core.theme` / page template ids there.
- Frontend Core leftovers: remove the BS3 `<script>` tags in `src/Frontend/Core/Layout/Templates/Base.html.twig:61-62`;
  strip the `bootstrap-sass` import from `src/Frontend/Core/Layout/Sass/screen.scss` and
  `src/Frontend/Modules/Search/Layout/Sass/Search.scss`. Keep the CSS files themselves: `Theme::getPath()`
  (`src/Frontend/Core/Engine/Theme.php:29`) throws when a referenced file is missing, and the Bootstrap theme overrides
  `Core/Layout/Css/screen.css` anyway. Replace their compiled BS3 content with an empty placeholder.

### Phase 2: delete dead assets and packages (`chore:`)
- Delete unused: `src/Backend/Core/Js/highcharts.js`, `src/Backend/Core/Js/jquery/jquery.ui.dialog.patch.js`,
  `js/vendors/bootstrap.bundle.js` (BS4). Remove from `package.json` `standard.ignore` where listed.
- Remove npm packages with zero references (re-grep incl. `src/Frontend/Themes/Bootstrap` before each):
  `bootstrap` (4.x, deprecated), `popper.js` (deprecated), `lodash`. Keep `requestanimationframe` (Bootstrap theme
  `Utilities/Resize.js`) and `@fancyapps/fancybox` (opt-in import in the Bootstrap theme starter).

### Phase 3: Sass on Bootstrap 5 + Font Awesome 6 (`feat:`)
- `src/Backend/Core/Layout/Sass/screen.scss`: import BS5 through the alias the way the Bootstrap theme does
  (reference `src/Frontend/Themes/Bootstrap/src/Layout/Sass/_bootstrap-imports.scss`): functions, our variables,
  variables, maps, mixins, utilities, then components.
- Rewrite `_bootstrap-variables.scss` as a small BS5 override file: `$primary #2F77CF`, `$success #068820`,
  `$warning #F5A623`, `$danger #d0021b`, `$body-bg #fafafa`, `$body-color #4a4a4a`, Open Sans font stack, keep custom
  `$brand-primary-dark/-darker`, `$gray-lightest`, `$content-background`, `$formbuilder-blue`, `$footer-height`.
  Drop `_fork-variables.scss` xl tier (BS5 has `xl` 1200 / `xxl` 1400; map the old 1440 usage to `xxl`).
- Partials: `min-width: $screen-*` -> `@include media-breakpoint-up(...)`; `.panel*` -> `.card*` (`_panels`,
  `_installer`, `_formbuilder`, `_editor`, `_pages`); `_navbar`: `.navbar-default/-header/-toggle` + `.open` ->
  BS5 equivalents and `.show`; `_tabs`: `.nav-tabs .nav-link`; `_forms`: `.form-label`, `.form-check`, `.form-text`,
  `.invalid-feedback`, `.input-group-text`; `_buttons`: drop `.btn-xs`/`.btn-default` overrides; `.label` -> `.badge`;
  `.close` -> `.btn-close`; drop the ~45 `ui-datepicker` rules from `plugins/_jquery_ui.scss`.
- Font Awesome: replace `font-awesome` 4 scss import with `@fortawesome/fontawesome-free` 6 scss (`fontawesome`,
  `solid`, `regular`, `brands`), copy its webfonts in `webpack.backend.js`, replace `font-family: FontAwesome` and
  `@extend .fa-*` usages (`_navbar.scss:9,248`, `_ckeditor.scss`).
- `src/ForkCMS/Bundle/InstallerBundle/Resources/public/css/installer.css`: update BS3 selectors.

### Phase 4: templates and PHP-emitted markup (`feat:`)
- Macros first: `src/Backend/Core/Layout/Templates/macros.html.twig` (`buttonIcon` 293 calls, `icon` 89, `required`
  112, `infoTooltip` 14). `buttonIcon` defaults to `btn-default` and renders the FA icon, so fixing it covers a large
  share of buttons/icons/tooltips in one place.
- `src/Backend/Core/Layout/Templates/FormLayout.html.twig` (421 l, wired via `app/config/form.yml:2`): extend
  `bootstrap_5_layout.html.twig`; port the custom blocks (`form_label`, `checkbox_radio_label`, `form_errors`,
  `meta_widget`, `fork_image_widget`, `fork_file_widget`, `bootstrap_collection_row`, `form_row`, `editor_row`, `help`,
  `collection_row`). Reference: `src/Frontend/Themes/Bootstrap/Core/Layout/Templates/FormLayouts/bootstrap_5_layout.html.twig`.
  `MediaLibrary/Resources/views/FormLayout.html.twig` (`media_group_widget`) too.
- Installer: `app/config/config_install.yml:44` points to a stale bundle path
  (`ForkCMSInstallerBundle:Installer:form_theme.html.twig`, views folder deleted in 3e3e55bd6); point it at
  `templates/Installer/form_theme.html.twig` and port that theme's hand-written BS3 markup (`has-error`,
  `form-horizontal/inline`, `input-group-addon`). Port `templates/Installer/*.html.twig` (`col-md-offset-1`,
  `hidden-xs`/`visible-xs`, `list-group`, FA icons).
- `pages.html.twig` and `dashboard.html.twig` duplicate the `base.html.twig` skeleton instead of extending it: apply
  the same structural edits to all three (or make them extend base, only if it is a no-op for the output).
- Tabs: 28x `<li role="presentation" class="active">` -> `active` on `a.nav-link`; BS4 `mr-auto` (11x) -> `me-auto`.
- Header: language + user dropdowns (`header.html.twig:20,40`): `data-bs-toggle="dropdown"`, `dropdown-menu-end`,
  `navbar-right` -> `ms-auto`. Breadcrumb: `breadcrumb-item` on each `<li>`.
- Out of scope: `Mails/*.html.twig` (inline email CSS).
- Mechanical class map across `src/Backend/**/Layout/Templates`, `src/Common`, `templates/Installer`, `*.tpl`, PHP:
  `panel/-heading/-body/-footer/-title` -> `card/-header/-body/-footer/-title`; `col-xs-N` -> `col-N`;
  `col-*-offset-N` -> `offset-*-N`; `pull-left/right` -> `float-start/end`; `btn-default` -> `btn-outline-secondary`;
  `btn-xs` -> `btn-sm`; `label label-*` -> `badge text-bg-*`; `form-group` -> `mb-3`; `control-label` -> `form-label`;
  `help-block` -> `form-text`; `has-error` -> `is-invalid` + `invalid-feedback`; `checkbox`/`radio` -> `form-check`;
  `input-group-addon` -> `input-group-text`; `hidden`/`hidden-xs` -> `d-none`/`d-sm-block`; `sr-only` ->
  `visually-hidden`; `img-responsive` -> `img-fluid`; `table-condensed` -> `table-sm`; `data-toggle/target/dismiss/
  parent` -> `data-bs-*`; `close` button -> `btn-close`; tabs: `nav-item` on `<li>`, `nav-link` on `<a>`, `active` on
  the link.
- Structural templates (keep the exact region layout): `base.html.twig`, `header.html.twig`, `navigation.html.twig`,
  `subnavigation.html.twig`, `pages.html.twig`, `dashboard.html.twig`, `empty.html.twig`, `footer.html.twig`,
  `breadcrumb.html.twig`, `Datagrid.tpl`, `DatagridPaging.tpl` (pagination: `page-item`/`page-link`).
- PHP markup: `DataGridFunctions.php:216`, mass-action `data-target` options (e.g. `Blog/Actions/Comments.php:130`,
  `Profiles/Actions/Index.php:148`), other PHP HTML strings found by grep.
- FA4 -> FA6 icon class rename in `macro.icon()`/`buttonIcon()` (`fa fa-X` -> `fa-solid fa-X`), the
  `DataGrid.php:28-42` action->icon map, 47 literal template hits, 19 PHP, 8 JS. Rename the 41 distinct names where
  FA6 differs: `floppy-o`->`floppy-disk`, `trash-o`->`trash-can` (regular), `file-o`->`file` (regular),
  `pencil`->`pen`, `refresh`->`arrows-rotate`, `reorder`->`bars`, `warning`->`triangle-exclamation`,
  `arrows-h/v`->`arrows-left-right`/`arrows-up-down`, `sign-in`->`right-to-bracket`, `rotate-left/right`,
  `close`->`xmark`, `clock-o`, `dot-circle-o`, `sort-asc/desc`->`sort-up/down`, `repeat`, `header`->`heading`,
  `thumbs-o-up`. Build the full map once and apply it with a script, then grep for leftovers.
- CKEditor content templates `src/Backend/Core/Layout/EditorTemplates/templates.js`: `pull-*` -> `float-*`.

### Phase 5: JavaScript on Bootstrap 5 (`feat:`)
- `webpack.backend.js`: copy `bootstrap5/dist/js/bootstrap.bundle.min.js` (includes Popper) instead of the
  bootstrap-sass JS; `src/Backend/Core/Engine/Header.php:83` keeps the same `/js/vendors/` name or points to the new one.
- `backend.js:554` `enforceFocus` override: BS5 uses a FocusTrap. Disable it for modals that host a CKEditor instance
  (`focus: false` option / `data-bs-focus="false"`) so CKEditor dialogs stay usable.
- `backend.js:1251`: quote the attribute selector (`a[href="#id"]`, `[data-bs-target="#id"]`).
- Replace `data-toggle`/`data-dismiss` strings built in JS (`backend.js:1853`, `MediaLibraryHelper.js:759,783,785`).
- Class names in JS strings: `panel*` (Pages.js), `btn-default`/`btn-xs`, `hidden`, `sr-only`, `img-responsive`,
  `navbar-default .navbar-nav` (`backend.js:144-248`), `tagClass` (`backend.js:1552`).
- Fix `MediaLibrary.js:88` `.modal('close')` -> `.modal('hide')`.
- Tooltips: keep explicit `.tooltip()` init (BS5 is opt-in, same as BS3).

### Phase 6: replace BS3-coupled plugins (`feat:` per plugin, separate commits)
- **Tags**: `bootstrap-tagsinput` + committed `typeahead.bundle.min.js` -> `tom-select` (vet package first per
  security rules) for `.js-tags-input` (`backend.js:1529-1557`, AJAX tag source). Remove both from `Header.php:80-97`.
  Keep `typeahead.bundle.min.js` file: Frontend Search still uses it.
- **Accessibility plugin**: remove `<script>` from `base/pages/empty/dashboard.html.twig`, CSS from `Header.php:95`,
  package + copy entries.
- **Dates**: `Common\Core\Form::addDate()` (`src/Common/Core/Form.php:120`) -> `type="date"` with mask `Y-m-d` and
  `min`/`max` from `from`/`till`/`range`; verify `FormDate` parsing/validation with the new mask; remove the jQuery UI
  datepicker init (`backend.js:1331-1413`). `src/Common/Form/DatePickerType.php`: `html5 => true`, default format,
  stop adding bootstrap-datepicker; remove the `fork-datepicker` init (`backend.js:1614`). Drop
  `bootstrap-datepicker` package.
- **Cropper**: `cropper` (jQuery) -> `cropperjs` 1.x; rewrite the 15 `.cropper(...)` calls in
  `MediaLibraryHelper.js:930-1130` to `new Cropper(img, options)` + instance methods; update copy entries and
  `MediaGroupType.php:206-209`.
- **jQuery UI**: `jquery-ui-dist` 1.12 -> `jquery-ui` 1.14 (`dist/jquery-ui.min.js`); used for sortable, droppable,
  `effect('highlight')`.
- `jquery-migrate` stays at 1.4 (jstree depends on it), handled in the jstree follow-up brief.

### Phase 7: drop BS3 packages
- Remove `bootstrap-sass`, `font-awesome`, `bootstrap-tagsinput`, `bootstrap-datepicker`,
  `bootstrap-accessibility-plugin`, `cropper` from `package.json`; remove their copy entries and committed copies in
  `js/vendors`, `css/vendors`, `fonts/vendors`. Final grep for `bootstrap-sass|glyphicon|panel-|col-xs-|data-toggle=|
  btn-default|font-awesome` returns nothing in backend/installer/frontend core.

## Verification

- `npm run build` and `npm test` (standard), `stylelint .`, `php bin/twig-cs-fixer lint templates/`,
  `php bin/phpcs`, `php bin/phpstan analyse`, `composer test` (unit + functional + installer suites).
- Manual run in the browser (user validates, no commits without approval), per screen with before/after screenshots:
  - login + forgot password, dashboard, navigation collapse/expand, mobile width
  - Pages: tree, add/edit with blocks modal, image upload (SimpleAjaxUploader), templates switch
  - Blog add/edit (dates, tags with Tom Select, CKEditor incl. dialogs inside modals), comments mass actions
  - MediaLibrary: upload (fine-uploader), cropper dialog, folders tree, media browser modal
  - FormBuilder: field modals, sortable fields; Faq: drag-and-drop categories; Extensions themes/modules
  - Locale, Settings, Users, Profiles (mass action modals), Groups
  - Fresh install via the installer (`/install`) ends on the Bootstrap theme and a working frontend
- Browser console free of errors on each screen; no 404s for vendor assets.

## Size estimate
Roughly 4 to 6 working days for the full branch: phases 1-2 half a day, Sass 1 day, templates/PHP 1.5-2 days (about
120 templates, 17 PHP files), JS + plugins 1-1.5 days, manual verification across all modules 1 day.

## Open risks
- Native date inputs change the submitted format: every `addDate` caller must still parse (`Y-m-d`).
- CKEditor 4 dialogs inside BS5 modals depend on the focus-trap workaround.
- jstree 0.9.9a markup/CSS lives in `_tree.scss`; keep its `.open` class untouched (jstree state, not BS).
