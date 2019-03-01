'use strict';

var gulp = require('gulp');
var sass = require('gulp-sass');

sass.compiler = require('node-sass');

gulp.task('sass', function () {
  return gulp.src('./Resources/assets/scss/**/*.scss')
    .pipe(sass({
      includePaths: ['node_modules/']
    }).on('error', sass.logError))
    .pipe(gulp.dest('./Resources/public/css'));
});

gulp.task('sass:watch', function () {
  gulp.watch('./sass/**/*.scss', ['sass']);
});
