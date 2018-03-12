var gulp = require('gulp'),
    concat = require('gulp-concat'),
    rename = require('gulp-rename'),
    sass = require('gulp-sass'),
    merge = require('merge-stream'),
    del = require('del');

// Move all PHP files to the build/theme folder
gulp.task('move-php-files', function(done){
    gulp.src(['src/*.php', '!src/config.dist.php'])
        .pipe(gulp.dest('build/theme'));

    gulp.src('src/lib/*.php')
        .pipe(gulp.dest('build/theme/lib'));

    // Inform Gulp this task is done
    done();
});

// Move WP Bootstrap Walker
gulp.task('move-bootstrap-walker', function(done){
    gulp.src('vendor/wp-bootstrap/wp-bootstrap-navwalker/class-wp-bootstrap-navwalker.php')
        .pipe(gulp.dest('build/theme/lib'));

    // Inform Gulp this task is done
    done();
});

gulp.task('clean-build', function (done) {
    return del(['build/**/*']);
});

// Build CSSes from SASSes
gulp.task('build-css', function (done) {
    var cssStream = gulp.src('src/style.css');
    var sassStream = gulp.src('src/scss/style.scss')
        .pipe(sass.sync().on('error', sass.logError))
        .pipe(gulp.dest('build/tmp'));

    var mergedStream = merge(cssStream, sassStream)
        .pipe(concat('test.css'))
        .pipe(rename('style.css'))
        .pipe(gulp.dest('build/theme'));

    done();
});

// Include Bootstrap Bundle
gulp.task('move-bootstrap-bundle', function (done) {
    gulp.src('node_modules/bootstrap/dist/js/bootstrap.bundle.js')
        .pipe(gulp.dest('build/theme/js'));

    done();
});

gulp.task('build', gulp.series(
    'clean-build',
    'build-css',
    'move-bootstrap-bundle',
    'move-php-files',
    'move-bootstrap-walker'
));
