var del         = require('del');
var fs          = require('fs');
var gulp        = require('gulp');
var gutil       = require('gulp-util');
var livereload  = require('gulp-livereload');
var plumber     = require('gulp-plumber');
var rename      = require('gulp-rename');
var path        = require('path');

var browsers    = '> 1% in GB, ie 11, last 2 versions';
var projectPath = __dirname;
var sourcePath  = projectPath + '/app/assets';
var targetPath  = projectPath + '/public/assets';
var bowerPath   = projectPath + '/bower_components';

var stylesGlob    = '/styles/**/*.{scss,css}';
var scriptsGlob   = '/scripts/**/*.js';
var phpGlob       = '/**/views/**/*.php';

/* Error Handler */

var errorHandler = function(error) {
    gutil.log(error.toString());
    this.emit('end');
};

/* Tasks */

gulp.task('watch', function() {
    livereload.listen();
    gulp.watch(sourcePath + stylesGlob, ['styles']);
    gulp.watch(sourcePath + scriptsGlob, ['scripts-default']);
    gulp.watch(projectPath + phpGlob, function(ev) {
        livereload.reload();
    });
});

gulp.task('default', function() {
    var sequence = require('run-sequence');
    return sequence(
        'clean',
        'copy-default',
        'copy-bower',
        'styles',
        'scripts-default',
        'scripts-editor-content',
        'scripts-editor-code'
    );
});

gulp.task('clean', function() {
    return del([targetPath + '/**']);
});

gulp.task('copy', function() {
    var sequence = require('run-sequence');
    return sequence(
        'copy-default',
        'copy-bower'
    );
});
gulp.task('copy-default', function() {
    return gulp.src([
            sourcePath + '/fonts/**/*.*',
            sourcePath + '/images/**/*.*',
        ], {base: sourcePath})
        .pipe(gulp.dest(targetPath));
});
gulp.task('copy-bower', function() {
    return gulp.src([
            bowerPath + '/tinymce/skins/lightgray' + '/**/*.*'
        ], {base: bowerPath})
        .pipe(gulp.dest(targetPath + '/vendor'));
});

/* Styles */

gulp.task('styles', function() {
    var anylink      = require('postcss-pseudo-class-any-link');
    var autoprefixer = require('autoprefixer');
    var cssnano      = require('cssnano');
    var include      = require('gulp-include');
    var position     = require('postcss-position');
    var postcss      = require('gulp-postcss');
    var sass         = require('gulp-sass');
    var vmin         = require('postcss-vmin');
    var willchange   = require('postcss-will-change');
    return gulp.src([
        sourcePath + '/styles/styles.scss',
        sourcePath + '/styles/editor-content.scss',
    ], {base: sourcePath})
        .pipe(plumber({errorHandler: errorHandler}))
        .pipe(include({extensions: ['scss', 'css'], hardFail: true}))
        .pipe(sass({
            outputStyle: 'expanded',
            precision: 8
        }))
        .pipe(gulp.dest(targetPath))
        .pipe(postcss([
            position(),
            autoprefixer({
                browsers: browsers,
                cascade: false
            }),
            vmin(),
            willchange(),
            anylink()
        ]))
        .pipe(gulp.dest(targetPath))
        .pipe(livereload())
        .pipe(postcss([
            cssnano()
        ]))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest(targetPath));
});

/* Scripts */

gulp.task('scripts', function() {
    var sequence = require('run-sequence');
	return sequence(
		'scripts-default',
		'scripts-editor-content',
		'scripts-editor-code'
	);
});
gulp.task('scripts-default', function() {
    var include = require('gulp-include');
    var uglify  = require('gulp-uglify');
	return gulp.src([
        sourcePath + '/scripts/*.js',
        '!**/_*.js',
        '!**/editor-*.js'
    ], {base: sourcePath})
		.pipe(include({extensions: ['js']}))
		.pipe(gulp.dest(targetPath))
		.pipe(livereload())
		.pipe(uglify())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath));
});
gulp.task('scripts-editor-content', function() {
    var include = require('gulp-include');
    var uglify  = require('gulp-uglify');
	return gulp.src([
        sourcePath + '/scripts/editor-content.js',
        '!**/_*.js'
    ], {base: sourcePath})
		.pipe(include({extensions: ['js']}))
		.pipe(gulp.dest(targetPath))
		.pipe(livereload())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath));
});
gulp.task('scripts-editor-code', function() {
    var include = require('gulp-include');
    var uglify  = require('gulp-uglify');
	return gulp.src([
        sourcePath + '/scripts/editor-code.js',
        '!**/_*.js'
    ], {base: sourcePath})
		.pipe(include({extensions: ['js']}))
		.pipe(gulp.dest(targetPath))
		.pipe(livereload())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath));
});