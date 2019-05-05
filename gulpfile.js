'use strict';

var
    chalk           = require('chalk'),
    gulp            = require('gulp'),
    pump            = require('pump'),
    browserSync     = require('browser-sync').create(),
    clean           = require('gulp-clean'),
    plumber         = require('gulp-plumber'),
    gulpif          = require('gulp-if'),
    gutil           = require('gulp-util'),
    jshint          = require('gulp-jshint'),
    uglify          = require('gulp-uglify'),
    concat          = require('gulp-concat'),
    stylus          = require('gulp-stylus'),
    autoprefix      = require('gulp-autoprefixer'),
    sourcemaps      = require('gulp-sourcemaps'),
    replace         = require('gulp-replace'),
    minifyCss       = require('gulp-minify-css'),
    stylish         = require('jshint-stylish'),
    merge           = require('merge-stream'),
    sequence        = require('run-sequence'),
    babel           = require('gulp-babel'),

    flags           = require('minimist')(process.argv.slice(2)),
    isProduction    = flags.production || flags.prod || false,
    needSourcemaps  = flags.sourcemaps || flags.sm || false,
    watching        = flags.watch || false
;

// BUILD ------------------------------------------------------------------------ //

var build = {
    css: {
        name: 'app.css',
        dest: 'public/build/css'
    },
    js: {
        name: 'app.js',
        dest: 'public/build/js'
    },
    jsVendor: {
        name: 'app.vendor.js',
        dest: 'public/build/js'
    }
};

gulp.task('jshint', function() {
    return gulp.src([
        'resources/assets/js/*.js',
        'resources/assets/js/**/*.js',
        // 'resources/assets/vendor/*.js',
        // 'resources/assets/vendor/**/*.js',
        // 'resources/assets/vendor/**/**/*.js'
    ])
        .pipe(plumber())
        .pipe(jshint('.jshintrc'))
        .pipe(jshint.reporter(stylish, { verbose: true }));
});

gulp.task('build', function(callback) {
    console.log(chalk.green('Building ' + (isProduction ? 'production' : 'dev') + ' version...'));

    if (flags.watch) {
        sequence(
            'clean',
            [
                'js',
                'css'
            ],
            'watch',
            function() {
                callback();
                console.log(chalk.green('Big brother is watching you...'))
            }
        )
    } else {
        sequence(
            'clean',
            [
                'js',
                'css'
            ],
            function() {
                callback();
                console.log(chalk.green('✔ All done!'))
            }
        )
    }
});
// CLEAN ------------------------------------------------------------------------ //

gulp.task('clean', function() {
    return gulp.src([
            'public/build/css',
            'public/build/js'
        ])
        .pipe(clean());
});

// CSS ------------------------------------------------------------------------ //

gulp.task('css', function() {
    return gulp.src([
        'resources/assets/css/reset.css',

        // vendors
        'resources/assets/vendor/bootstrap/4.0.0/css/bootstrap.css',

        // core
        'resources/assets/css/fonts.css',
        'resources/assets/css/global.styl',

        // components
        'resources/assets/css/components/*.styl',
        'resources/assets/css/components/**/*.styl'
    ])
        .pipe(plumber())
        .pipe(stylus({
            'include css': true,
            compress: !! isProduction
        }))
        .pipe(concat(build.css.name))
        .pipe(autoprefix('last 2 version', 'ie 8', 'ie 9'))
        .pipe(gulpif(isProduction, minifyCss({keepSpecialComments: 0})))
        .pipe(gulp.dest(build.css.dest))
        .pipe(gulpif(watching, browserSync.stream()));
});

// JS ------------------------------------------------------------------------ //
gulp.task('js', function() {
    var core = gulp.src([
        'resources/assets/js/core.js',
        'resources/assets/js/modules/*.js',
        'resources/assets/js/widgets/**/*.js'
    ])
    .pipe(plumber())
    .pipe(concat(build.js.name))

    .pipe(gulpif(isProduction, uglify()))
    .pipe(gulp.dest(build.js.dest));

    var vendor = gulp.src([
        'resources/assets/vendor/bootstrap/4.0.0/js/bootstrap.js'
    ])
    .pipe(plumber())
    .pipe(concat(build.jsVendor.name))
    .pipe(gulpif(isProduction, uglify()))
    .pipe(gulp.dest(build.jsVendor.dest));

    return merge(core, vendor);
});

// WATCH ------------------------------------------------------------------------ //
gulp.task('watch', function() {
    // Watch .css files
    gulp.watch('resources/assets/css/*.css', ['css']);
    gulp.watch('resources/assets/css/*.styl', ['css']);
    gulp.watch('resources/assets/css/*/*.styl', ['css']);
    gulp.watch('resources/assets/css/*/*/*.styl', ['css']);
    gulp.watch('resources/assets/css/*/*/*/*.styl', ['css']);

    // Watch .js files
    gulp.watch('resources/assets/js/**/*.js', ['js']);
});


// DEFAULT ---------------------------------------------------------------------- //
gulp.task('default', function() {
    gulp.start('build');
});