const gulp = require( 'gulp' ),
	concat = require( 'gulp-concat' ),
	exec = require( 'child_process' ).exec,
	gulpIf = require( 'gulp-if' ),
	rename = require( 'gulp-rename' ),
	sass = require( 'gulp-sass' ),
	makeDir = require( 'make-dir' ),
	merge = require( 'merge-stream' ),
	del = require( 'del' ),
	fs = require( 'fs' ),
	lazypipe = require( 'lazypipe' );

gulp.task( 'clean-build', function() {
	return del( [ 'build/**/*' ] );
} );

// Move all PHP files to the build/theme folder
gulp.task( 'move-php-files', function( done ) {
	gulp.src( [ 'src/*.php', '!src/config.dist.php', '!src/functions.dist.php' ] )
		.pipe( gulp.dest( 'build/theme' ) );

	gulp.src( 'src/lib/*.php' )
		.pipe( gulp.dest( 'build/theme/lib' ) );

	// Inform Gulp this task is done
	done();
} );

// Move Gutenberg blocks
gulp.task( 'move-blocks', function( done ) {
	const processScss = lazypipe()
		.pipe( sass.sync )
		.pipe( rename, function( path ) {
			path.extname = '.css';
		} );

	gulp.src( [ 'src/blocks/**/*' ] )
		.pipe( gulpIf( '*.scss', processScss() ) )
		.pipe( gulp.dest( 'build/theme/blocks' ) );

	done();
} );

// Move the miniature of the theme to the build/theme folder
gulp.task( 'move-screenshot', function( done ) {
	gulp.src( [ 'src/screenshot.png' ] )
		.pipe( gulp.dest( 'build/theme' ) );

	// Inform Gulp this task is done
	done();
} );

// Move WP Bootstrap Walker
gulp.task( 'move-bootstrap-walker', function( done ) {
	gulp.src( 'vendor/wp-bootstrap/wp-bootstrap-navwalker/class-wp-bootstrap-navwalker.php' )
		.pipe( gulp.dest( 'build/theme/lib' ) );

	// Inform Gulp this task is done
	done();
} );

// Move images
gulp.task( 'move-images', function( done ) {
	gulp.src( [ 'src/images/*' ] )
		.pipe( gulp.dest( 'build/theme/images' ) );

	// Inform Gulp this task is done
	done();
} );

// Build icons fonts
gulp.task( 'build-icons-fonts', function( done ) {
	if ( ! fs.existsSync( 'build/theme/icons' ) ) {
		makeDir( 'build/theme/icons' );
	}

	exec( 'node_modules/.bin/webfont "src/scss/custom/elements/icons/*.svg" --dest build/theme/icons', function() {
		done();
	} );
} );

// Build CSSes from SASSes
gulp.task( 'build-css', function( done ) {
	// Load the SCSS files
	const scssFiles = [ 'src/scss/style.scss' ];
	if ( fs.existsSync( 'src/scss/auto-include' ) ) {
		scssFiles.push( 'src/scss/auto-include/**/*.scss' );
	}
	const sassStream = gulp.src( scssFiles )
		.pipe( sass.sync().on( 'error', sass.logError ) )
		.pipe( gulp.dest( 'build/tmp' ) );

	// Load additional CSS files
	const cssFiles = [ 'src/style.css' ];
	if ( fs.existsSync( 'src/css' ) ) {
		cssFiles.push( 'src/css/**/*.css' );
	}
	const styleCssStream = gulp.src( cssFiles );

	merge( styleCssStream, sassStream )
		.pipe( concat( 'test.css' ) )
		.pipe( rename( 'style.css' ) )
		.pipe( gulp.dest( 'build/theme' ) );

	done();
} );

gulp.task( 'build-js', function( done ) {
	const jsFiles = [ 'node_modules/bootstrap/dist/js/bootstrap.bundle.js' ];

	if ( fs.existsSync( 'src/js' ) ) {
		jsFiles.push( 'src/js/**/*.js' );
	}

	gulp.src( jsFiles )
		.pipe( concat( 'test.js' ) )
		.pipe( rename( 'scripts.js' ) )
		.pipe( gulp.dest( 'build/theme/js' ) );

	done();
} );

gulp.task( 'build', gulp.series(
	'clean-build',
	'move-images',
	'move-php-files',
	'move-blocks',
	'move-bootstrap-walker',
	'move-screenshot',
	'build-icons-fonts',
	'build-css',
	'build-js',
) );
