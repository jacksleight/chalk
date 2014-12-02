var gulp		= require('gulp'),
	gutil		= require('gulp-util'),
	cached		= require('gulp-cached'),
	clean		= require('gulp-clean'),
	compass		= require('gulp-compass'),
	imagemin	= require('gulp-imagemin'),
	include		= require('gulp-include'),
	livereload	= require('gulp-livereload'),
	minifycss	= require('gulp-minify-css'),
	rename		= require('gulp-rename'),
	replace		= require('gulp-replace'),
	sprites		= require('gulp-include'),
	svg2png		= require('gulp-svg2png'),
	svgmin		= require('gulp-svgmin'),
	uglify		= require('gulp-uglify'),
	sequence 	= require('run-sequence'),
	server		= require('tiny-lr')(),
	sprite 		= require('css-sprite').stream,
	path		= require('path');

var projectPath	= __dirname,
	bowerName	= 'bower_components',
	viewsName	= 'app/views',
	sourceName	= 'app/assets',
	targetName	= 'public/assets',
	bowerPath	= projectPath + '/' + bowerName,
	viewsPath	= projectPath + '/' + viewsName,
	sourcePath	= projectPath + '/' + sourceName,
	targetPath	= projectPath + '/' + targetName,
	stylesDir	= '/styles',
	scriptsDir	= '/scripts',
	imagesDir	= '/images',
	fontsDir	= '/fonts',
	flashDir	= '/flash',
	templatesDir= '/templates';

/* Tasks */

gulp.task('default', function() {
	return sequence(
		'clean',
		'copy-default',
		'copy-bower',
		'images-icons',
		'images-general',
		'scripts-default',
		'scripts-editor-content',
		'scripts-editor-code',
		'styles'
	);
});

gulp.task('watch', function() {
	server.listen(35729, function(err) {
		if (err) {
			return console.log(err)
		}
		gulp.watch(sourcePath + stylesDir + '/**/*.scss', ['styles']);
		gulp.watch(sourcePath + scriptsDir + '/**/*.js', ['scripts']);
		gulp.watch(viewsPath + '/**/*.php', function(ev) {
			server.changed({body: {files: [ev.path]}});
		});
	});
});

gulp.task('clean', function() {
	return gulp.src(targetPath, {read: false})
		.pipe(clean());
});

gulp.task('copy', function() {
	return sequence(
		'copy-default',
		'copy-bower'
	);
});
gulp.task('copy-default', function() {
	return gulp.src([
			sourcePath + fontsDir + '/**/*.{ttf,otf,woff,woff2,svg,eot}',
			sourcePath + flashDir + '/**/*.swf',
		], {base: sourcePath})
		.pipe(gulp.dest(targetPath));
});
gulp.task('copy-bower', function() {
	return gulp.src([
			bowerPath + '/tinymce/skins/lightgray' + '/**/*.*',
		], {base: bowerPath})
		.pipe(gulp.dest(targetPath + '/vendor'));
});

/* Images */

gulp.task('images', function() {
	return sequence(
		'images-clean',
		'images-general',
		'images-icons'
	);
});
gulp.task('images-clean', function() {
	return gulp.src(targetPath + imagesDir, {read: false})
		.pipe(clean());
});
gulp.task('images-general', function() {
	return gulp.src(sourcePath + imagesDir + '/*.svg')
		.pipe(svgmin([{removeViewBox: true}]))
		.pipe(gulp.dest(targetPath + imagesDir));
});
gulp.task('images-icons', function() {
	return gulp.src(sourcePath + imagesDir + '/icon/*.svg')
		.pipe(svgmin([{removeViewBox: true}]))
		.pipe(rename(function(path) {
			path.basename = path.basename
				.replace(/^iconmonstr-/, '')
				.replace(/-icon$/, '');
		}))
		.pipe(replace(/<(path|rect|circle|ellipse|line|polyline|polygon)/g, '<$1 fill="#fff"'))
		.pipe(gulp.dest(targetPath + imagesDir + '/icon-light'))
		.pipe(replace('#fff', '#2d353c'))
		.pipe(gulp.dest(targetPath + imagesDir + '/icon-dark'));
});

/* Scripts */

gulp.task('scripts', function() {
	return sequence(
		'scripts-default',
		'scripts-editor-content',
		'scripts-editor-code'
	);
});
gulp.task('scripts-default', function() {
	return gulp.src([sourcePath + scriptsDir + '/*.js', '!**/_*.js', '!**/editor-*.js'])
		.pipe(include({extensions: ['js']}))
		.pipe(cached('scripts'))
		.pipe(gulp.dest(targetPath + scriptsDir))
		.pipe(livereload(server))
		.pipe(uglify())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath + scriptsDir));
});
gulp.task('scripts-editor-content', function() {
	return gulp.src([sourcePath + scriptsDir + '/editor-content.js', '!**/_*.js'])
		.pipe(include({extensions: ['js']}))
		.pipe(cached('scripts'))
		.pipe(gulp.dest(targetPath + scriptsDir))
		.pipe(livereload(server))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath + scriptsDir));
});
gulp.task('scripts-editor-code', function() {
	return gulp.src([sourcePath + scriptsDir + '/editor-code.js', '!**/_*.js'])
		.pipe(include({extensions: ['js']}))
		.pipe(cached('scripts'))
		.pipe(gulp.dest(targetPath + scriptsDir))
		.pipe(livereload(server))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath + scriptsDir));
});

/* Styles */

gulp.task('styles', function() {
	var c;
	return gulp.src([sourcePath + stylesDir + '/*.scss', '!**/_*.scss'])
		.pipe(c = compass({
			config_file: projectPath + '/compass.rb',
			project: projectPath,
			sass: sourceName + stylesDir,
			css: targetName + stylesDir
		}).on('error', function(e) { c.end; this.emit('end'); }))
		.pipe(cached('styles'))
		.pipe(livereload(server))
		.pipe(minifycss())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath + stylesDir));
});