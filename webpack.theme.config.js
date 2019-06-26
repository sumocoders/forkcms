const webpack = require('webpack')
const path = require('path')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const ManifestPlugin = require('webpack-manifest-plugin')
const glob = require('glob-all')
const TerserPlugin = require('terser-webpack-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin')
const PurgecssPlugin = require('purgecss-webpack-plugin')

const buildPath = path.resolve(__dirname, 'dist')
const publicPath = `/src/Frontend/Themes/Bootstrap4/dist/`

// Custom PurgeCSS extractor for Tailwind that allows special characters in
// class names.
//
// https://github.com/FullHuman/purgecss#extractor
class TailwindExtractor {
  static extract(content) {
    return content.match(/[A-Za-z0-9-_:\/]+/g) || [];
  }
}

module.exports = {
  entry: './src/Frontend/Themes/Bootstrap4/Core/Js/Index.js',
  output: {
    path: path.resolve(__dirname, './src/Frontend/Themes/Bootstrap4/dist/'),
    publicPath: publicPath,
    filename: '[name].[chunkhash].js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: 'babel-loader',
      },
      {
        test: /\.(css|scss)$/,
        use: [
          MiniCssExtractPlugin.loader,
          // 'style-loader', // creates style nodes from JS strings
          'css-loader', // translates CSS into CommonJS
          'postcss-loader', // Apply PostCSS plugins defined in postcss.config.js
          'sass-loader' // compiles Sass to CSS, using Node Sass by default
        ]
      },
      {
        test: /\.(png|jpg|gif)$/,
        use: [
          {
            loader: 'file-loader',
            options: {
              outputPath: 'images'
            }
          }
        ]
      }
    ]
  },
  plugins: [
    new CleanWebpackPlugin(),

    // Lightweight CSS extraction plugin built on top of features available in Webpack v4 (performance!).
    new MiniCssExtractPlugin({
      filename: '[name].[contenthash].css',
      chunkFilename: '[id].css',
    }),

    // Generate an asset manifest file, so we can leverage Symfony 3.3's Manifest-based asset versioning
    // See https://symfony.com/blog/new-in-symfony-3-3-manifest-based-asset-versioning
    new ManifestPlugin({
      publicPath,
      writeToFileEmit: true, // Make sure manifest is created on webpack-dev-server too!
    }),

    new webpack.ProvidePlugin({
      jQuery: 'jquery',
      $: 'jquery',
      'window.jQuery': 'jquery',
    }),

    // PurgeCSS to keep the css size small by removing unused css.
    new PurgecssPlugin({
      // Specify the locations of any files you want to scan for class names.
      paths: glob.sync([
        path.join(__dirname, 'src/Frontend/Themes/Bootstrap4/Core/Layout/Templates/**/*.twig'),
        path.join(__dirname, 'src/Frontend/Themes/Bootstrap4/Core/Layout/Templates/**/*.html'),
        path.join(__dirname, 'src/Frontend/Themes/Bootstrap4/Core/Js/**/*.js'),
        path.join(__dirname, 'src/Frontend/Themes/Bootstrap4/Modules/**/Layout/**/*.twig'),
      ]),
      whitelistPatternsChildren: [/^content|^editor/],
      extractors: [
        {
          extractor: TailwindExtractor,

          // Specify the file extensions to include when scanning for
          // class names.
          extensions: ['html', 'twig', 'js', 'php'],
        },
      ],
    }),

    new webpack.optimize.ModuleConcatenationPlugin(),
  ],
  optimization: {
    minimize: true,
    splitChunks: {
      // Extract css into one css file
      cacheGroups: {
        // Extract all non-dynamic imported node_modules imports into a vendor file
        vendor: {
          chunks: 'initial',
          name: 'vendor',
          test: /node_modules/,
          enforce: true,
        },
        // Extract css into one css file
        styles: {
          name: 'styles',
          test: /\.css$/,
          chunks: 'all',
          enforce: true,
        },
      },
    },
    minimizer: [
      new TerserPlugin({
        cache: true,
        parallel: true,
        sourceMap: false
      }),
    ],
  },
  // watch: true
};
