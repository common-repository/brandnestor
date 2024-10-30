const cssnano = require('cssnano');
const gulp = require('gulp');
const postcss = require('gulp-postcss');
const sass = require('gulp-sass')(require('sass'));
const webpack = require('webpack');
const webpackStream = require('webpack-stream');
const webpackDefaultConfig = require('./webpack.config.default.js');
const webpackBlocksConfig = require('./webpack.config.blocks.js');

gulp.task('css', function() {
  return gulp.src('./assets/css/src/*.css')
    .pipe(sass())
    .pipe(postcss([
      cssnano(),
    ]))
    .pipe(gulp.dest('./assets/css/build'));
});

gulp.task('js', function() {
  return webpackStream(webpackDefaultConfig, webpack)
    .pipe(gulp.dest('./assets/js/build'));
});

gulp.task('blocks', function() {
  return webpackStream(webpackBlocksConfig, webpack)
    .pipe(gulp.dest('./assets/js/build/blocks'));
});

gulp.task('default', gulp.parallel('css', 'js', 'blocks'));

gulp.task('watch', function() {
  webpackDefaultConfig['mode'] = 'development';
  gulp.watch('./assets/css/src/*.css', gulp.series('css'));
  gulp.watch('./assets/js/src/*.js', gulp.series('js'));
  gulp.watch('./assets/js/src/blocks/**/*.js', gulp.series('blocks'));
});
