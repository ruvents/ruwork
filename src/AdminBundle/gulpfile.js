'use strict'

var gulp = require('gulp'),
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    cssnano = require('gulp-cssnano'),
    rename = require('gulp-rename'),
    livereload = require('gulp-livereload'),
    suppressError = function (error) {
        console.log(error.toString())
        this.emit('end')
    },
    postcss = require('gulp-postcss'),
    concat = require('gulp-concat'),
    del = require('del')

gulp.task('clean', function () {
    return del.sync([
        'Resources/public/{css,js}'
    ])
})

gulp.task('scss', function () {
    return gulp
        .src('Resources/assets/scss/app.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .on('error', suppressError)
        .pipe(postcss([require('autoprefixer')]))
        .pipe(cssnano({zindex: false}))
        .pipe(sourcemaps.write())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('css', {cwd: 'Resources/public'}))
        .pipe(livereload())
})

gulp.task('scripts', function () {
    return gulp
        .src([
            'Resources/assets/scripts/!(app)*.js',
            'Resources/assets/scripts/app.js'
        ])
        .pipe(concat('app.js'))
        .pipe(gulp.dest('js', {cwd: 'Resources/public'}))
})

gulp.task('vendor', function () {
    return gulp
        .src([
            'node_modules/bootstrap-markdown/locale/bootstrap-markdown.ru.js'
        ], {base: './'})
        .pipe(gulp.dest('vendor', {cwd: 'Resources/public'}))
})

gulp.task('watch', function () {
    livereload.listen()

    gulp.watch('Resources/assets/scss/**/*.scss', ['scss'])
    gulp.watch('Resources/assets/scripts/**/*.js', ['scripts'])
})

gulp.task('default', ['clean', 'watch', 'scss', 'scripts', 'vendor'])

gulp.task('build', ['scss', 'vendor'])
