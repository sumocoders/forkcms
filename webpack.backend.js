const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts')
const CopyPlugin = require('copy-webpack-plugin')

// copies node_modules files flat into a folder in the project root, untouched by the minimizer
const copyVendorFiles = (files, folder) => files.map((file) => ({
  from: `node_modules/${file}`,
  to: path.resolve(__dirname, folder, '[name][ext]'),
  info: { minimized: true }
}))

const ckeditorPlugins = [
  'clipboard',
  'colordialog',
  'copyformatting',
  'dialog',
  'dialogadvtab',
  'div',
  'docprops',
  'iframe',
  'image',
  'link',
  'liststyle',
  'pastefromword',
  'specialchar',
  'table',
  'tabletools',
  'stylesheetparser',
  'templates',
  'uicolor',
  'widget',
  'wsc'
]

const ckeditorFiles = [
  'adapters/*.js',
  'lang/*.js',
  // a glob keeps the plugins/ folder, a plain file path would be copied flat
  'plugins/icons{,_hidpi}.png',
  ...ckeditorPlugins.map((plugin) => `plugins/${plugin}/**/*.{js,png,jpg,jpeg,gif,css,html,svg}`),
  'skins/moono-lisa/**/*.{css,png,gif}',
  'ckeditor.js',
  'contents.css',
  'styles.js',
  'LICENSE.md'
]

module.exports = {
  name: 'backend',
  entry: {
    screen: './src/Backend/Core/Layout/Sass/screen.scss',
    debug: './src/Backend/Core/Layout/Sass/debug.scss'
  },
  output: {
    path: path.resolve(__dirname, 'src/Backend/Core/Layout/Css'),
    // the output folder holds committed files, never clean it
    clean: false
  },
  mode: 'development',
  // the maps are committed; sass-loader always embeds the Sass sources, which grows screen.css.map sevenfold
  devtool: 'nosources-source-map',
  optimization: {
    // webpack 5.112 minifies CSS and HTML assets in production by default; sass already compresses the committed css
    minimizeOptions: { css: false, html: false }
  },
  watchOptions: {
    aggregateTimeout: 600,
    ignored: /node_modules/
  },
  plugins: [
    new RemoveEmptyScriptsPlugin(),
    new MiniCssExtractPlugin({
      filename: '[name].css'
    }),
    new CopyPlugin({
      patterns: [
        ...copyVendorFiles([
          'tom-select/dist/css/tom-select.bootstrap5.min.css',
          'bootstrap-datepicker/dist/css/bootstrap-datepicker3.standalone.min.css',
          'cropperjs/dist/cropper.min.css',
          'bootstrap-accessibility-plugin/plugins/css/bootstrap-accessibility.css'
        ], 'css/vendors'),
        ...copyVendorFiles([
          'fine-uploader/jquery.fine-uploader/fine-uploader-new.min.css',
          'fine-uploader/jquery.fine-uploader/*.gif',
          'fine-uploader/jquery.fine-uploader/placeholders/waiting-generic.png',
          'fine-uploader/jquery.fine-uploader/placeholders/not_available-generic.png'
        ], 'css/vendors/fine-uploader'),
        ...copyVendorFiles([
          'font-awesome/fonts/*',
          '@fortawesome/fontawesome-free/webfonts/fa-solid-900.{woff2,ttf}',
          '@fortawesome/fontawesome-free/webfonts/fa-regular-400.{woff2,ttf}'
        ], 'fonts/vendors'),
        ...copyVendorFiles([
          'jquery/dist/jquery.min.js',
          'jquery-migrate/dist/jquery-migrate.min.js',
          'jquery-ui/dist/jquery-ui.min.js',
          'bootstrap5/dist/js/bootstrap.bundle.min.js',
          'tom-select/dist/js/tom-select.complete.min.js',
          'fine-uploader/jquery.fine-uploader/jquery.fine-uploader.min.js',
          'simple-ajax-uploader/SimpleAjaxUploader.min.js',
          'bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
          'cropperjs/dist/cropper.min.js',
          'bootstrap-accessibility-plugin/plugins/js/bootstrap-accessibility.min.js'
        ], 'js/vendors'),
        ...ckeditorFiles.map((file) => ({
          from: file,
          context: path.resolve(__dirname, 'node_modules/ckeditor'),
          to: path.resolve(__dirname, 'src/Backend/Core/Js/ckeditor'),
          info: { minimized: true },
          // the npm package ships its js with a UTF-8 BOM, the committed copies have none
          transform: (content, absoluteFrom) => absoluteFrom.endsWith('.js')
            ? Buffer.from(content.toString('utf8').replace(/^\uFEFF/, ''))
            : content
        }))
      ]
    })
  ],
  module: {
    rules: [
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              // keep the absolute /fonts and /src urls as they are
              url: false
            }
          },
          {
            loader: 'postcss-loader'
          },
          {
            loader: 'sass-loader',
            options: {
              sassOptions: {
                loadPaths: [path.resolve(__dirname, 'node_modules')],
                quietDeps: true
              }
            }
          }
        ]
      }
    ]
  }
}
