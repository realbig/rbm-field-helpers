'use strict';

import plugins from 'gulp-load-plugins';
import browser from 'browser-sync';
import yargs from 'yargs';
import gulp from 'gulp';
import rimraf from 'rimraf';
import yaml from 'js-yaml';
import fs from 'fs';
import webpackStream from 'webpack-stream';
import webpack2 from 'webpack';
import named from 'vinyl-named';
import path from 'path';

const packageFile = require('./package.json');

// Load all Gulp plugins into one variable
const $ = plugins();

// Check for --production flag
const PRODUCTION = !!(yargs.argv.production);

// Check for --noserver flag
const SERVER = !(yargs.argv.noserver);

// Load settings from settings.yml
const {COMPATIBILITY, PATHS} = loadConfig();

function loadConfig() {
    let ymlFile = fs.readFileSync('config.yml', 'utf8');
    return yaml.load(ymlFile);
}

// Enter URL of your local server here
// Example: 'http://localhost:8888'
var URL = 'http://src.wordpress-develop.dev';

// Build the "dist" folder by running all of the below tasks
gulp.task('build',
    PRODUCTION ?
        gulp.series(clean, version, sass, sassMin, rtl, javascript, javascriptMin, buildSelect2, copy, copyVendorLicenses, packageFiles) :
        gulp.series(clean, sass, sassMin, rtl, javascript, javascriptMin, buildSelect2, copy, copyVendorLicenses));

// Build the site and watch for file changes
gulp.task('default', gulp.series('build', watch) );

// Delete the "dist" folder
// This happens every time a build starts
function clean(done) {
    rimraf(PATHS.dist, done);
}

// Copy files out of the assets folder
// This task skips over the "img", "js", and "scss" folders, which are parsed separately
function copy() {
    return gulp.src(PATHS.assets)
        .pipe(gulp.dest(PATHS.dist + '/assets'));
}

// Compile Sass into CSS
// In production, the CSS is compressed
function sass() {
    return gulp.src( PATHS.entries.scss, { allowEmpty: true } )
        .pipe($.sourcemaps.init())
        .pipe($.sass({
            includePaths: PATHS.sass
        })
            .on('error', $.sass.logError))
        .pipe($.autoprefixer({
            browsers: COMPATIBILITY
        }))
        .pipe($.if(!PRODUCTION, $.sourcemaps.write( '.' )))
        .pipe(gulp.dest(PATHS.dist + '/css'))
        .pipe(browser.reload({stream: true}));
}

function sassMin() {

    return gulp.src( PATHS.entries.scss, { allowEmpty: true } )
        .pipe($.sourcemaps.init())
        .pipe($.sass({
            includePaths: PATHS.sass,
            outputStyle: 'compressed'
        }).on('error', $.sass.logError))
        .pipe($.rename(function (file) {

			if ( file.extname.indexOf( '.min' ) < 0 ) {
				file.extname = '.min' + file.extname;
			}
		  
		}))
        .pipe($.if(!PRODUCTION, $.sourcemaps.write( '.' )))
        .pipe(gulp.dest(PATHS.dist + '/css'))
        .pipe(browser.reload({stream: true}));

}

function rtl() {

    return gulp.src( [ 
        PATHS.dist + '/css/**/*.css', 
        '!' + PATHS.dist + '/css/**/*-rtl.css',
        '!' + PATHS.dist + '/css/**/rbm-fh-select2*.css',
    ], { allowEmpty: true } )
      .pipe( named() )
      .pipe( $.rename( function( file ) {
        if ( file.basename.indexOf( '.min' ) >= 0 ) {
            file.basename = file.basename.replace( '.min', '.min-rtl' );
        }
        else {
            file.basename = file.basename + '-rtl';
        }
        return file;
      } ) )
      .pipe(
        $.rtlcss()
      )
      .pipe(
        $.replace( /\/\*# sourceMappingURL.*$/, '' )
      )
      .pipe(
        gulp.dest( PATHS.dist + '/css' )
      );

}

let webpackConfig = {
    module: {
        rules: [
            {
                test: /.js$/,
                use: [
                    {
                        loader: 'babel-loader'
                    }
                ]
            }
        ]
    },
	externals: {
		'jquery': 'jQuery',
		'jquery-ui': 'jQuery.ui',
	},
}

if ( ! PRODUCTION ) {
    webpackConfig.devtool = 'inline-source-map';
}

// Combine JavaScript into one file
// In production, the file is minified
function javascript() {
    return gulp.src(PATHS.entries.js)
        .pipe(named())
        .pipe(webpackStream(webpackConfig, webpack2))
        .pipe($.sourcemaps.init( { loadMaps: true }))
        .pipe($.if(!PRODUCTION, $.sourcemaps.write('.' )))
        .pipe(gulp.dest(PATHS.dist + '/js'));
}

function javascriptMin() {
    return gulp.src(PATHS.entries.js)
        .pipe(named())
        .pipe(webpackStream(webpackConfig, webpack2))
        .pipe($.sourcemaps.init( { loadMaps: true }))
        .pipe($.uglify()
            .on('error', e => {
                console.log(e);
            })
        )
        .pipe($.rename(function (file) {

			if ( file.extname.indexOf( '.min' ) < 0 ) {
				file.extname = '.min' + file.extname;
			}
		  
		}))
        .pipe($.if(!PRODUCTION, $.sourcemaps.write('.' )))
        .pipe(gulp.dest(PATHS.dist + '/js'));
}

function buildSelect2( done ) {
	
	// Remove the Factory to replace with our own
	let select2file = fs.readFileSync( __dirname + '/node_modules/select2/dist/js/select2.full.js', 'utf8' );
	select2file = select2file.replace( /\(function \(factory\) {[\s\S]*} \(function \(jQuery\) {/m, '(function (jQuery) {' );

	// Grab the Select2 Code
	let select2code = select2file.match( /\(function \(jQuery\) {[\s\S]*}\)\);/m )[0];
	
	// Append to our own Factory
	let rbmselect2code = fs.readFileSync( __dirname + '/assets/src/js/admin/rbm-fh-select2.js', 'utf8' );
	fs.writeFileSync( __dirname + '/assets/dist/js/rbm-fh-select2.js', rbmselect2code + ' ' + select2code, 'utf8' );

    return gulp.src( 'assets/dist/js/rbm-fh-select2.js', { allowEmpty: true } )
        .pipe(named())
        .pipe($.uglify()
            .on('error', e => {
                console.log(e);
            })
        )
        .pipe($.rename(function (file) {
            console.log( file );
			if ( file.extname.indexOf( '.min' ) < 0 ) {
				file.extname = '.min' + file.extname;
			}
		  
		}))
        .pipe(gulp.dest(PATHS.dist + '/js'));
	
}

function copyVendorLicenses() {
	
	return gulp.src(PATHS.licenses, { allowEmpty: true, base: '*' })
		.pipe($.rename(function(file) {
			file.dirname = file.dirname.replace( '../node_modules/', '' );
			return file;
		 }))
        .pipe(gulp.dest(PATHS.dist + '/licenses/'));
	
}

// Start BrowserSync to preview the site in
function server(done) {
    browser.init({
        proxy: URL,

        ui: {
            port: 8080
        },

    });
    done();
}

// Reload the browser with BrowserSync
function reload(done) {
    browser.reload();
    done();
}

gulp.task( 'version', version );

function version() {
    return gulp.src([
        'assets/src/**/*',
        'vendor/**/*',
        'core/**/*',
        'rbm-field-helpers.php'
    ], {base: './'})
    //     Doc block versions
        .pipe($.replace(/\{\{VERSION}}/g, packageFile.version))
        // Version constant
        .pipe($.replace(/(define\( 'RBM_FIELD_HELPERS_VER', ')\d+\.\d+\.\d+/, "$1" + packageFile.version))
        .pipe(gulp.dest('./'));
}

function packageFiles() {
    return gulp.src([
        'assets/dist/**/*',
        '!assets/dist/**/*.map',
        'vendor/**/*',
        'core/**/*',
        'rbm-field-helpers.php'
    ], {base: './'})
        .pipe($.zip(`rbm-field-helpers-${packageFile.version}.zip`))
        .pipe(gulp.dest('./'));
}

// Watch for changes to Sass, and JavaScript
function watch() {
    gulp.watch(PATHS.assets, copy);
    gulp.watch('assets/src/scss/admin/**/*.scss').on('all', gulp.series( sass, sassMin, rtl ) );
    gulp.watch('assets/src/js/admin/**/*.js').on('all', javascript, javascriptMin );
}
