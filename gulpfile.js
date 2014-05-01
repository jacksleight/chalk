var gulp		= require('gulp'),
	cached		= require('gulp-cached'),
	compass		= require('gulp-compass'),
	imagemin	= require('gulp-imagemin'),
	include		= require('gulp-include'),
	livereload	= require('gulp-livereload'),
	minifycss	= require('gulp-minify-css'),
	rename		= require('gulp-rename'),
	svg2png		= require('gulp-svg2png'),
	svgmin		= require('gulp-svgmin'),
	uglify		= require('gulp-uglify'),
	sequence 	= require('run-sequence'),
	server		= require('tiny-lr')();
	path		= require('path');

var projectDir		= __dirname + '/ayre',
	controllersDir	= projectDir + '/controllers',
	libDir			= projectDir + '/lib',
	viewsDir		= projectDir + '/views',
	assetsDir		= projectDir + '/assets',
	stylesDir		= assetsDir + '/styles',
	scriptsDir		= assetsDir + '/scripts',
	imagesDir		= assetsDir + '/images',
	buildDir		= assetsDir + '/build';

gulp.task('styles', function() {
	return sequence(
		'styles-compass',
		'styles-min',
		'styles-sprites');
});
gulp.task('styles-compass', function() {
	return gulp.src(stylesDir + '/*.scss')
		.pipe(compass({
			config_file: projectDir + '/compass.rb',
			project: projectDir,
			sass: 'assets/styles',
			css: 'assets/build'
		}));
});
gulp.task('styles-min', function() {
	return gulp.src([buildDir + '/*.css', '!**/*.min.css'])
		.pipe(cached('styles'))
		.pipe(livereload(server))
		.pipe(minifycss())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(buildDir));
});
gulp.task('styles-sprites', function() {
	return gulp.src(imagesDir + '/{1x,2x}-*.png')
		.pipe(imagemin())
		.pipe(gulp.dest(imagesDir));
});

gulp.task('scripts', function() {
	return gulp.src([scriptsDir + '/*.js', '!**/_*.js', '!**/editor.js'])
		.pipe(include({extensions: ['js']}))
		.pipe(cached('scripts'))
		.pipe(gulp.dest(buildDir))
		.pipe(livereload(server))
		.pipe(uglify())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(buildDir));
});
gulp.task('scripts-editor', function() {
	return gulp.src([scriptsDir + '/editor.js'])
		.pipe(include({extensions: ['js']}))
		.pipe(cached('scripts'))
		.pipe(gulp.dest(buildDir))
		.pipe(livereload(server))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(buildDir));
});

gulp.task('images', function() {
	return gulp.start('vector');
});
gulp.task('vector', function() {
	return sequence(
		'vector-min',
		['vector-1x', 'vector-2x'],
		'raster');
});
gulp.task('vector-min', function() {
	return gulp.src(imagesDir + '/*.svg')
		.pipe(svgmin([{removeViewBox: true}]))
		.pipe(gulp.dest(imagesDir));
});
gulp.task('vector-1x', function() {
	return gulp.src(imagesDir + '/*.svg')
		.pipe(svg2png(1.0))
		.pipe(gulp.dest(imagesDir + '/1x'));
});
gulp.task('vector-2x', function() {
	return gulp.src(imagesDir + '/*.svg')
		.pipe(svg2png(2.0))
		.pipe(gulp.dest(imagesDir + '/2x'));
});
gulp.task('raster', function() {
	return gulp.src(imagesDir + '/**/*.{gif,png,jpg}')
		.pipe(imagemin({progressive: true, interlaced: true}))
		.pipe(gulp.dest(imagesDir));
});

gulp.task('default', function() {
	return sequence(
		'vector-min',
		['vector-1x', 'vector-2x'],
		'styles-compass',
		'styles-min',
		'raster',
		'scripts');
});

gulp.task('watch', function() {
	server.listen(35729, function(err) {
		if (err) {
			return console.log(err)
		}
		gulp.watch([stylesDir + '/**/*.scss'], ['styles']);
		gulp.watch([scriptsDir + '/**/*.js', '!**/editor.js'], ['scripts']);
		gulp.watch([scriptsDir + '/**/editor.js'], ['scripts-editor']);
		gulp.watch([
			controllersDir + '/**/*.php',
			libDir + '/**/*.php',
			viewsDir + '/**/*.php'
		], function(ev) {
			server.changed({
				body: {
					files: [ev.path]
				}
			});
		});
	});
});