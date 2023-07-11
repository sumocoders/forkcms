const path = require("path");
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const CopyPlugin = require("copy-webpack-plugin")
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
const Package = require('./package.json')
const theme = Package.theme

module.exports = {
  entry: {
    screen: [`./src/Frontend/Themes/${theme}/src/Js/Index.js`, `./src/Frontend/Themes/${theme}/src/Layout/Sass/screen.scss`]
  },
  output: {
    filename: 'bundle.js',
    path: path.resolve(__dirname, `src/Frontend/Themes/${theme}/Core`),
    clean: true,
  },
  mode: "development",
  externals: ['jquery'],
  watchOptions: {
    aggregateTimeout: 600,
    ignored: /node_modules/,
  },
  plugins: [
    new MiniCssExtractPlugin({
      filename: './Layout/Css/[name].css'
    }),
    new CopyPlugin({
      patterns: [
        {
          from: "./Layout/Templates/**/*",
          context: path.resolve(__dirname, "src", "Frontend", "Themes", theme, "src"),
        },
        {
          from: "./Layout/Images/**/*",
          context: path.resolve(__dirname, "src", "Frontend", "Themes", theme, "src"),
        }
      ],
    }),
    new BrowserSyncPlugin({
      host: '127.0.0.1',
      port: 3000,
      proxy: 'https://127.0.0.1:8000/'
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
            loader: 'sass-loader',
            options: {
              sourceMap: true,
            }
          }
        ]
      },
      {
        test: /\.(woff(2)?|ttf|svg|eot)$/,
        type: 'asset/resource',
        generator: {
          filename: './Layout/Fonts/[name][ext]',
        },
      },
    ]
  }
}
