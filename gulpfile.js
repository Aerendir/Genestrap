var gulp = require('gulp');

// Move all PHP files to the build/theme folder
gulp.task('move-php-files', function(done){
    gulp.src(['src/*.php', '!src/config.dist.php'])
        .pipe(gulp.dest('build/theme'));

    gulp.src('src/lib/*.php')
        .pipe(gulp.dest('build/theme/lib'));

    // Inform Gulp this task is don
    done();
});

// Move style.css to the vuild/theme folder
gulp.task('move-style-css', function(done){
    gulp.src('src/style.css')
        .pipe(gulp.dest('build/theme'));

    // Inform Gulp this task is don
    done();
});

gulp.task('build', gulp.series(
    'move-style-css',
    'move-php-files'
));