const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const CopyPlugin = require('copy-webpack-plugin')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const PackageJson = require('./package.json')
const theme = PackageJson.theme

module.exports = {
  entry: {
    bundle: [
      `./src/Frontend/Themes/${theme}/src/Js/Index.js`
    ],
    icons: [
      `./src/Frontend/Themes/${theme}/src/Js/Icons.js`
    ],
    screen: [
      `./src/Frontend/Themes/${theme}/src/Layout/Sass/screen.scss`
    ],
    editor_content: [
      `./src/Frontend/Themes/${theme}/src/Layout/Sass/editor_content.scss`
    ]
  },
  output: {
    chunkFilename: (pathData) => {
      return pathData.chunk.name === 'bundle' ? 'bundle.js' : '[name].js'
    },
    path: path.resolve(__dirname, `src/Frontend/Themes/${theme}/Core`),
    clean: true
  },
  mode: 'development',
  externals: ['jquery'],
  watchOptions: {
    aggregateTimeout: 600,
    ignored: /node_modules/
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: './Layout/Css/[name].css'
    }),
    new CopyPlugin({
      patterns: [
        {
          from: './Layout/Templates/**/*',
          context: path.resolve(__dirname, 'src', 'Frontend', 'Themes', theme, 'src')
        },
        {
          from: './Layout/Images/**/*',
          context: path.resolve(__dirname, 'src', 'Frontend', 'Themes', theme, 'src')
        }
      ]
    }),
    // This setup assumes symfony server is running on https://127.0.0.1:8000. If that is not the case, edit 'proxy' below.
    new BrowserSyncPlugin({
      host: '127.0.0.1',
      port: 3000,
      proxy: 'https://127.0.0.1:8000/',
      notify: false
    })
  ],
  module: {
    rules: [
      {
        test: /\.js$|jsx/,
        loader: 'babel-loader',
        exclude: /node_modules/
      },
      {
        // separate loader for Bootstrap because it needs to be compiled
        test: /bootstrap\.js$/,
        loader: 'babel-loader'
      },
      {
        test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader'
          },
          {
            loader: 'postcss-loader'
          },
          {
            loader: 'sass-loader',
            options: {
              sourceMap: true
            }
          }
        ]
      },
      {
        test: /\.css$/,
        use: ['style-loader', 'css-loader', 'postcss-loader']
      },
      {
        test: /\.(woff(2)?|ttf|svg|eot)$/,
        type: 'asset/resource',
        generator: {
          filename: './Layout/Fonts/[name][ext]'
        }
      }
    ]
  }
}
