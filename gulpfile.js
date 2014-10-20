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
		'images-copy',
		'images-sprites-min',
		'images-sprites-1x',
		'images-sprites-2x',
		'images-min',
		'scripts-default',
		'scripts-editor',
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
			bowerPath + '/fontawesome/fonts' + '/**/*.*',
		], {base: bowerPath})
		.pipe(gulp.dest(targetPath + '/vendor'));
});

/* Images */

gulp.task('images', function() {
	return sequence(
		'images-clean',
		'images-copy',
		'images-sprites-min',
		'images-sprites-1x',
		'images-sprites-2x',
		'images-min'
	);
});
gulp.task('images-clean', function() {
	return gulp.src(targetPath + imagesDir, {read: false})
		.pipe(clean());
});
gulp.task('images-copy', function() {
	return gulp.src(sourcePath + imagesDir + '/**/*.{gif,png,jpg,ico,xml}', {base: sourcePath + imagesDir})
		.pipe(gulp.dest(targetPath + imagesDir));
});
gulp.task('images-sprites-min', function() {
	return gulp.src(sourcePath + imagesDir + '/*.svg')
		.pipe(svgmin([{removeViewBox: true}]))
		.pipe(gulp.dest(targetPath + imagesDir));
});
gulp.task('images-sprites-1x', function() {
	return gulp.src(targetPath + imagesDir + '/*.svg')
		.pipe(svg2png(1.0))
		.pipe(gulp.dest(targetPath + imagesDir + '/1x'))
		.pipe(sprite({
			name: 		 'all.png',
			style: 		 'all.png.scss',
			cssPath: 	 '../images',
			template: 	 sourceName + templatesDir + '/sprites.scss.mustache',
			prefix: 	 'sprite-1x',
			orientation: 'binary-tree',
			margin:      5
		}))
		.pipe(gulp.dest(targetPath + imagesDir + '/1x'));
});
gulp.task('images-sprites-2x', function() {
	return gulp.src(targetPath + imagesDir + '/*.svg')
		.pipe(svg2png(2.0))
		.pipe(gulp.dest(targetPath + imagesDir + '/2x'))
		.pipe(sprite({
			name: 		 'all.png',
			style: 		 'all.png.scss',
			cssPath:   	 '../images',
			template:  	 sourceName + templatesDir + '/sprites.scss.mustache',
			prefix: 	 'sprite-2x',
			orientation: 'binary-tree',
			margin:      10
		}))
		.pipe(gulp.dest(targetPath + imagesDir + '/2x'));
});
gulp.task('images-min', function() {
	return gulp.src(targetPath + imagesDir + '/**/*.{gif,png,jpg}', {base: targetPath + imagesDir})
		.pipe(imagemin({progressive: true, interlaced: true}))
		.pipe(gulp.dest(targetPath + imagesDir));
});

/* Scripts */

gulp.task('scripts', function() {
	return sequence(
		'scripts-default',
		'scripts-editor'
	);
});
gulp.task('scripts-default', function() {
	return gulp.src([sourcePath + scriptsDir + '/*.js', '!**/_*.js', '!**/editor.js'])
		.pipe(include({extensions: ['js']}))
		.pipe(cached('scripts'))
		.pipe(gulp.dest(targetPath + scriptsDir))
		.pipe(livereload(server))
		.pipe(uglify())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath + scriptsDir));
});
gulp.task('scripts-editor', function() {
	return gulp.src([sourcePath + scriptsDir + '/editor.js', '!**/_*.js'])
		.pipe(include({extensions: ['js']}))
		.pipe(cached('scripts'))
		.pipe(gulp.dest(targetPath + scriptsDir))
		.pipe(livereload(server))
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath + scriptsDir));
});

/* Styles */

gulp.task('styles', function() {
	var c = compass({
		config_file: projectPath + '/compass.rb',
		project: projectPath,
		sass: sourceName + stylesDir,
		css: targetName + stylesDir
	});
	c.on('error', function(e) {
		gutil.log(e);
		c.end();
	});
	return gulp.src([sourcePath + stylesDir + '/*.scss', '!**/_*.scss'])
		.pipe(c)
		.pipe(cached('styles'))
		.pipe(livereload(server))
		.pipe(minifycss())
		.pipe(rename({suffix: '.min'}))
		.pipe(gulp.dest(targetPath + stylesDir));
});