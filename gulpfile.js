const gulp = require('gulp'),
  concat = require('gulp-concat'),
  rename = require('gulp-rename'),
  sass = require('gulp-sass'),
  merge = require('merge-stream'),
  del = require('del'),
  fs = require('fs');


// Move all PHP files to the build/theme folder
gulp.task('move-php-files', function(done){
  gulp.src(['src/*.php', '!src/config.dist.php', '!src/functions.dist.php'])
    .pipe(gulp.dest('build/theme'));

  gulp.src('src/lib/*.php')
    .pipe(gulp.dest('build/theme/lib'));

  // Inform Gulp this task is done
  done();
});

// Move the miniature of the theme to the build/theme folder
gulp.task('move-screenshot', function(done){
  gulp.src(['src/screenshot.png'])
    .pipe(gulp.dest('build/theme'));

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

gulp.task('clean-build', function () {
  return del(['build/**/*']);
});

// Build CSSes from SASSes
gulp.task('build-css', function (done) {
  const sassStream = gulp.src('src/scss/style.scss')
    .pipe(sass.sync().on('error', sass.logError))
    .pipe(gulp.dest('build/tmp'));

  let cssFiles = ['src/style.css'];
  if (fs.existsSync('src/css')) {
    cssFiles.push('src/css/**/*.css');
  }
  let styleCssStream = gulp.src(cssFiles);

  merge(styleCssStream, sassStream)
    .pipe(concat('test.css'))
    .pipe(rename('style.css'))
    .pipe(gulp.dest('build/theme'));

  done();
});

gulp.task('build-js', function (done) {
  let jsFiles = ['node_modules/bootstrap/dist/js/bootstrap.bundle.js'];

  if (fs.existsSync('src/js')) {
    jsFiles.push('src/js/**/*.js');
  }

  gulp.src(jsFiles)
    .pipe(concat('test.js'))
    .pipe(rename('scripts.js'))
    .pipe(gulp.dest('build/theme/js'));

  done();
});

// Move icon fonts
gulp.task('move-icon-fonts', function (done) {
  if (fs.existsSync('src/scss/custom/elements/icons/build')) {
    gulp.src([
      'src/scss/custom/elements/icons/build/custom-icons.eot',
      'src/scss/custom/elements/icons/build/custom-icons.svg',
      'src/scss/custom/elements/icons/build/custom-icons.ttf',
      'src/scss/custom/elements/icons/build/custom-icons.woff',
      'src/scss/custom/elements/icons/build/custom-icons.woff2',
    ])
      .pipe(gulp.dest('build/theme/icons'));
  }

  done();
});

gulp.task('build', gulp.series(
  'clean-build',
  'build-css',
  'build-js',
  'move-php-files',
  'move-bootstrap-walker',
  'move-screenshot',
  'move-icon-fonts'
));
