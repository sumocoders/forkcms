/*jslint node: true */
"use strict";

const gulp = require("gulp");
const sass = require("gulp-sass");
const sourcemaps = require("gulp-sourcemaps");
const autoprefixer = require("gulp-autoprefixer");

gulp.task("build:backend:assets:copy-css-vendors", function() {
  return gulp.src([
    "./node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css",
    "./node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput-typeahead.css",
  ])
      .pipe(gulp.dest("./css/vendors"));
});

gulp.task("build:backend:assets:copy-fonts-vendors", function() {
  return gulp.src([
    "./node_modules/font-awesome/fonts/**",
  ])
      .pipe(gulp.dest("./fonts/vendors"));
});

gulp.task("build:backend:assets:copy-js-vendors", function() {
  return gulp.src([
    "./node_modules/jquery/dist/jquery.min.js",
    "./node_modules/jquery-migrate/dist/jquery-migrate.min.js",
    "./node_modules/jquery-ui-dist/jquery-ui.min.js",
    "./node_modules/bootstrap-sass/assets/javascripts/bootstrap.min.js",
    "./node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js",
  ])
      .pipe(gulp.dest("./js/vendors"));
});

gulp.task("build:backend:sass:generate-css", function() {
  return gulp.src([
    "./src/Backend/Core/Layout/Sass/screen.scss",
    "./src/Backend/Core/Layout/Sass/debug.scss",
  ])
      .pipe(sourcemaps.init())
      .pipe(sass({
        includePaths: [
          "./node_modules/"
        ],
        outputStyle:  "compressed",
        precision:    10
      }))
      .pipe(autoprefixer({}))
      .pipe(sourcemaps.write("./", {
        includeContent: false,
        sourceRoot:     "/src/Backend/Core/Layout/Sass"
      }))
      .pipe(gulp.dest("./src/Backend/Core/Layout/Css"));
});

gulp.task("build:backend", function() {
  gulp.start(
      "build:backend:assets:copy-css-vendors",
      "build:backend:assets:copy-fonts-vendors",
      "build:backend:assets:copy-js-vendors",
      "build:backend:sass:generate-css"
  );
});

// @remark: custom for SumoCoders
const fs = require("fs");
const del = require("del");
const plumber = require("gulp-plumber");
const iconfont = require("gulp-iconfont");
const livereload = require("gulp-livereload");
const consolidate = require("gulp-consolidate");
const rename = require("gulp-rename");
const fontgen = require("gulp-fontgen");
const webpackStream = require("webpack-stream");
const webpack = require("webpack");
const imagemin = require("gulp-imagemin");

const theme = JSON.parse(fs.readFileSync("./package.json")).theme;
const paths = {
  src:  `src/Frontend/Themes/${theme}/src`,
  core: `src/Frontend/Themes/${theme}/Core`
};

gulp.plumbedSrc = function() {
  return gulp.src.apply(gulp, arguments)
      .pipe(plumber());
};

gulp.task("build:assets:copy-images-vendors", function() {
    return gulp.src([
        "./node_modules/fancybox/dist/img/*"
    ])
        .pipe(gulp.dest("./images/vendors/fancybox"));
});

gulp.task("build:theme:empty-destination-folders", function() {
  return del([
    `${paths.core}/Layout/Fonts/**/*`,
    `${paths.core}/Layout/Images/**/*`,
    `${paths.core}/Layout/Templates/**/*`
  ]);
});

gulp.task("build:theme:fonts:generate-iconfont", function() {
  return gulp.plumbedSrc(`${paths.src}/Layout/icon-sources/*.svg`)
      .pipe(iconfont({fontName: "icons"}))
      .on("glyphs", function(glyphs) {
        var options = {
          glyphs:    glyphs,
          fontName:  "icons",
          fontPath:  "../Fonts/",
          className: "icon"
        };

        gulp.src(`${paths.src}/Layout/Sass/_icons-template.scss`)
            .pipe(consolidate("lodash", options))
            .pipe(rename({basename: "_icons"}))
            .pipe(gulp.dest(`${paths.src}/Layout/Sass`));
      })
      .pipe(gulp.dest(`${paths.core}/Layout/Fonts`))
      .pipe(livereload());
});

gulp.task("build:theme:fonts:generate-webfonts", function() {
  return gulp.plumbedSrc(`${paths.src}/Layout/Fonts/**/*.{ttf,otf}`)
      .pipe(fontgen({
        options: {
          stylesheet: false
        },
        dest:    `${paths.core}/Layout/Fonts/`
      }))
      .pipe(livereload());
});

gulp.task("build:theme:sass:generate-development-css", function() {
  return gulp.plumbedSrc(`${paths.src}/Layout/Sass/*.scss`)
      .pipe(sourcemaps.init())
      .pipe(sass({
        includePaths: [
          "./node_modules"
        ]
      }).on("error", sass.logError))
      .pipe(autoprefixer())
      .pipe(sourcemaps.write("./", {
        includeContent: false,
        sourceRoot:     `/src/Frontend/Themes/${theme}/src/Layout/Sass`
      }))
      .pipe(gulp.dest(`${paths.core}/Layout/Css`))
      .pipe(livereload());
});

gulp.task("build:theme:sass:generate-production-css", function() {
  return gulp.src(`${paths.src}/Layout/Sass/*.scss`)
      .pipe(sourcemaps.init())
      .pipe(sass({
        outputStyle:  "compressed",
        includePaths: [
          "./node_modules"
        ]
      }).on("error", sass.logError))
      .pipe(autoprefixer())
      .pipe(sourcemaps.write("./", {
        includeContent: false,
        sourceRoot:     `/src/Frontend/Themes/${theme}/src/Layout/Sass`
      }))
      .pipe(gulp.dest(`${paths.core}/Layout/Css`));
});

var commonWebpackConfig = {
  output:  {
    filename: "bundle.js"
  },
  devtool: "source-maps",
  module:  {
    loaders: [
      {
        test:    /.js?$/,
        loader:  "babel",
        exclude: /node_modules/
      }
    ]
  }
};

gulp.task("build:theme:webpack:generate-development-js", function() {
  return gulp.plumbedSrc(`${paths.src}/Js/Index.js`)
      .pipe(webpackStream(Object.assign({}, commonWebpackConfig, {
        watch: true
      })))
      .pipe(gulp.dest(`${paths.core}/Js`))
      .pipe(livereload());
});

gulp.task("build:theme:webpack:generate-production-js", function() {
  return gulp.src(`${paths.src}/Js/Index.js`)
      .pipe(webpackStream(Object.assign({}, commonWebpackConfig, {
        plugins: [
          new webpack.optimize.UglifyJsPlugin({
            compress: {
              warnings: false
            }
          }),
          new webpack.DefinePlugin({
            'process.env.NODE_ENV': '"production"'
          })
        ]
      }, webpack)))
      .pipe(gulp.dest(`${paths.core}/Js`));
});

gulp.task("build:theme:assets:copy-templates", function() {
  return gulp.plumbedSrc(`${paths.src}/Layout/Templates/**/*`)
      .pipe(gulp.dest(`${paths.core}/Layout/Templates`))
      .pipe(livereload());
});

gulp.task("build:theme:images:minify-images", function() {
  return gulp.plumbedSrc(`${paths.src}/Layout/Images/**/*`)
      .pipe(imagemin())
      .pipe(gulp.dest(`${paths.core}/Layout/Images`))
      .pipe(livereload());
});

gulp.task("build:theme", ["build:theme:empty-destination-folders"], function() {
  gulp.start(
      "build:assets:copy-images-vendors",
      "build:theme:fonts:generate-iconfont",
      "build:theme:fonts:generate-webfonts",
      "build:theme:sass:generate-production-css",
      "build:theme:webpack:generate-production-js",
      "build:theme:assets:copy-templates",
      "build:theme:images:minify-images"
  );
});

gulp.task("serve:theme", function() {
  livereload.listen();
  gulp.watch(`${paths.src}/Js/**/*.js`, ["build:theme:webpack:generate-development-js"]);
  gulp.watch(`${paths.src}/Layout/Sass/**/*.scss`, ["build:theme:sass:generate-development-css"]);
  gulp.watch(`${paths.src}/Layout/Templates/**/*`, ["build:theme:assets:copy-templates"]);
  gulp.watch(`${paths.src}/Layout/Images/**/*`, ["build:theme:images:minify-images"]);
  gulp.watch(`${paths.src}/Layout/icon-sources/*`, ["build:theme:fonts:generate-iconfont"]);
  gulp.watch(`${paths.src}/Layout/Fonts/**/*`, ["build:theme:fonts:generate-webfonts"]);
});

// public tasks
gulp.task("default", function() {
  gulp.start("build");
});

gulp.task("serve", function() {
  gulp.start("serve:theme");
});

gulp.task("build", function() {
  gulp.start(
      "build:backend",
      "build:theme" // @remark custom for SumoCoders
  );
});
