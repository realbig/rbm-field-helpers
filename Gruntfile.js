'use strict';
module.exports = function (grunt) {

    // load all grunt tasks
    require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        // Define watch tasks
        watch: {
            options: {
                livereload: true
            },
            sass: {
                files: ['assets/sass/**/*.scss', '!assets/sass/admin/**/*.scss'],
                tasks: ['sass:front', 'autoprefixer:front', 'notify:sass']
            },
            sass_admin: {
                files: ['assets/sass/admin/**/*.scss'],
                tasks: ['sass:admin', 'autoprefixer:admin', 'notify:sass_admin']
            },
            js: {
                files: ['assets/js/*.js'],
                tasks: ['uglify:front', 'notify:js']
            },
            js_admin: {
                files: ['assets/js/admin/*.js'],
                tasks: ['uglify:admin', 'notify:js_admin']
            },
            livereload: {
                files: ['**/*.html', '**/*.php', 'assets/images/**/*.{png,jpg,jpeg,gif,webp,svg}', '!**/*ajax*.php']
            }
        },

        // SASS
        sass: {
            options: {
                sourceMap: true
            },
            front: {
                files: {
                    'rbm-field-helpers-front.css': 'assets/sass/main.scss'
                }
            },
            admin: {
                files: {
                    'rbm-field-helpers-admin.css': 'assets/sass/admin/admin.scss'
                }
            }
        },

        // Auto prefix our CSS with vendor prefixes
        autoprefixer: {
            options: {
                map: true
            },
            front: {
                src: 'rbm-field-helpers-front.css'
            },
            admin: {
                src: 'rbm-field-helpers-admin.css'
            }
        },

        // Uglify and concatenate
        uglify: {
            options: {
                sourceMap: true
            },
            front: {
                files: {
                    'rbm-field-helpers-front.js': [
                        'assets/js/*.js'
                    ]
                }
            },
            admin: {
                files: {
                    'rbm-field-helpers-admin.js': [
                        'assets/js/admin/*.js'
                    ]
                }
            }
        },

        notify: {
            js: {
                options: {
                    title: '<%= pkg.name %>',
                    message: 'JS Complete'
                }
            },
            js_admin: {
                options: {
                    title: '<%= pkg.name %>',
                    message: 'JS Admin Complete'
                }
            },
            sass: {
                options: {
                    title: '<%= pkg.name %>',
                    message: 'SASS Complete'
                }
            },
            sass_admin: {
                options: {
                    title: '<%= pkg.name %>',
                    message: 'SASS Admin Complete'
                }
            }
        }

    });

    // Register our main task
    grunt.registerTask('Watch', ['watch']);
};