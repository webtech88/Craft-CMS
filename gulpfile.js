'use strict'

var gulp = require('gulp')
var sass = require('gulp-sass')
var css = require('gulp-clean-css')
var concat = require('gulp-concat')
var compiler = require('gulp-uglify')
var addsrc = require('gulp-add-src')
var autoprefixer = require('gulp-autoprefixer')
var minify = require('gulp-minify')

String.prototype.allFiles = function (inDir) {
  return this + (inDir ? inDir : '') + '**'
};

String.prototype.file = function (name) {
	return this + name
}

var assets = './web/assets/'
var styles = assets + 'css/'
var scripts = assets + 'js/'

// var stylesVendor = [
//
// ];

var scriptsVendor = [
  'node_modules/uikit/dist/js/uikit.min.js',
  'node_modules/uikit/dist/js/uikit-icons.min.js',
  'node_modules/axios/dist/axios.js',
  'node_modules/vue/dist/vue.min.js',
  'node_modules/vue2-google-maps/dist/vue-google-maps.js',
]

// Build
gulp.task('styles:watch', function () {
  return gulp.src(styles.file('site.scss'))
    .pipe(sass().on('error', sass.logError))
    // .pipe(addsrc.prepend(stylesVendor))
    .pipe(concat('styles.css'))
    .pipe(autoprefixer({
      browsers: 'dead',
      cascade: false,
    }))
    .pipe(gulp.dest(assets))
})

gulp.task('scripts:watch', function () {
  return gulp.src(scripts.file('site.js'))
    .pipe(addsrc.prepend(scriptsVendor))
    .pipe(concat('scripts.js'))
    .pipe(gulp.dest(assets))
})

gulp.task('watch', function () {
  gulp.watch(styles.allFiles(), ['styles:watch']);
  gulp.watch(scripts.allFiles(), ['scripts:watch']);
})

// Compile
gulp.task('styles:compile', function () {
  return gulp.src(styles.file('site.scss'))
    .pipe(sass())
    // .pipe(addsrc.prepend(stylesVendor))
    .pipe(concat('styles.css'))
    .pipe(autoprefixer({
      browsers: 'dead',
      cascade: false,
    }))
    .pipe(css())
    .pipe(gulp.dest(assets))
});

gulp.task('scripts:compile', function () {
  return gulp.src(scripts.file('site.js'))
    .pipe(addsrc.prepend(scriptsVendor))
    .pipe(concat('scripts.js'))
    .pipe(compiler())
    .pipe(minify({
      ext:{
        src:'.js',
        min:'.js',
      }
    }))
    .pipe(gulp.dest(assets))
})

gulp.task('compile', ['styles:compile', 'scripts:compile'])

gulp.task('default', ['watch'])
