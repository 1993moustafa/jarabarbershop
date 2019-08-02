'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');
var autoprefixer  = require('gulp-autoprefixer');
var watchSass = require("gulp-watch-sass");
var cleanCSS = require('gulp-clean-css');
var sourcemaps = require('gulp-sourcemaps');
var browserSync = require('browser-sync').create();

sass.compiler = require('node-sass');

gulp.task('sass', function () {
  return gulp.src('./style.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./'))
});


gulp.task('watch', function(){
  gulp.watch('./style.scss', gulp.series('sass'));
});