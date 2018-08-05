'use strict';

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    browserSync = require('browser-sync'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglifyjs'),
    cssnano = require('gulp-cssnano'),
    rename = require('gulp-rename'),
    del = require('del'),
    imagemin = require('gulp-imagemin'),
    pngquant = require('imagemin-pngquant'),
    cache = require('gulp-cache')
    ;

gulp.task('sass', function () {
    return gulp.src('html-src/sass/**/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('html-src/css'))
        .pipe(browserSync.reload({
            stream:true
        }));
});

gulp.task('scripts', function () {
    return gulp.src([
        'html-src/libs/jquery/dist/jquery.min.js',
        'html-src/libs/magnific-popup/dist/jquery.magnific-popup.min.js',
    ])
        .pipe(concat('libs.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('html-src/js'));
});

gulp.task('css-min',['sass'], function () {
    return gulp.src([
        'html-src/css/main.css',
    ])
        .pipe(cssnano())
        .pipe(rename({suffix:'.min'}))
        .pipe(gulp.dest('html-src/css'));
});

gulp.task('img', function () {
    return gulp.src([
        'html-src/img/**/*',
    ])
        .pipe(cache(imagemin({
            interlaced:true,
            progressive: true,
            svgoPlugins: [{removeViewBox: false}],
            use: [pngquant()]
        })))
        .pipe(gulp.dest('public/img'));
});

gulp.task('browser-sync',function () {
    browserSync({
        server: {
            baseDir: 'html-src'
        },
        notify:false
    });
});

gulp.task('watch',['browser-sync','css-min','scripts'],function () {
    gulp.watch('html-src/sass/**/*.scss',['sass']);
    gulp.watch('html-src/*.html',browserSync.reload);
    gulp.watch('html-src/js/**/*.js',browserSync.reload);
});


gulp.task('build',['clean','img','css-min','scripts'],function () {

    var buildCss = gulp.src([
        'html-src/css/main.min.css'
    ]).pipe(gulp.dest('public/css'));

    var buildFonts = gulp.src([
        'html-src/fonts/**/*'
    ]).pipe(gulp.dest('public/fonts'));

    var buildJs = gulp.src([
        'html-src/js/**/*.js'
    ]).pipe(gulp.dest('public/js'));

    var buildHtml = gulp.src([
        'html-src/*.html'
    ]).pipe(gulp.dest('public'));
});

gulp.task('clean',function () {

    return del.sync('public')
});

gulp.task('clearCache',function () {

    return cache.clearAll();
});