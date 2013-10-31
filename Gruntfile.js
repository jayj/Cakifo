module.exports = function(grunt) {

	// Load tasks.
	require('matchdep').filterDev('grunt-*').forEach( grunt.loadNpmTasks );

	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// Compile LESS
		less: {
			dev: {
				options: {
					strictMath: true,
					strictUnits: true
				},
				files: {
					'style.css': 'style.dev.less',
					'css/colorbox.css': 'css/colorbox.less',
				}
			}
		},
		// Compile Sass
		sass: {
			dev: {
				options: {
					style: 'compressed'
				},
				files: {
					'css/editor-style.css': 'css/editor-style.scss',
				}
			}
		},
		// Watch for file changes
		watch: {
			scripts: {
				files: ['js/script.js'],
				tasks: ['jshint']
			},
			less: {
				files: ['**/*.less'],
				tasks: ['less']
			},
			sass: {
				files: ['**/*.scss'],
				tasks: ['sass']
			},
			livereload: {
				options: {
					livereload: true,
				},
				files: [
					'style.css',
					'css/colorbox.css',
					'css/editor-style.css',
					'js/script.js'
				],
			}
		},
		// Minify CSS into *.min.css
		cssmin: {
			minify: {
				expand: true,
				src: [
					'style.css',
					'css/colorbox.css'
				],
				dest: 'build/',
				ext: '.min.css'
			}
		},
		// Minify images
		imagemin: {
			dynamic: {
				files: [{
					expand: true,
					cwd: 'images/',
					dest: 'images/',
					src: ['**/*.{png,jpg,gif}'],
				}]
			}
		},
		// Use jshint
		jshint: {
			all: ['js/script.js']
		},
		// Copy to build folder
		copy: {
			build: {
				src: [
					'**',
					// Files and folders to ignore
					'!docs/**',
					'!README.markdown',
					'!**/.{svn,git}/**',
					'!.gitignore',
					'!.gitmodules',
					'!node_modules/**',
					'!Gruntfile.js',
					'!package.json'
				],
				dest: 'build/',
			},
		},
		// Clean the build folder
		clean: {
			build: {
				src: ['build/']
			}
		},
		// Bump version numbers
		version: {
			css: {
				options: {
					prefix: 'Version\\:\\s'
				},
				src: ['style.dev.less'],
			},
			php: {
				options: {
					prefix: '\@version\\s+'
				},
				src: ['functions.php'],
			}
		},
		// Compress the build folder into an upload-ready zip file
		compress: {
			build: {
				options: {
					archive: 'build/cakifo.zip'
				},
				cwd: 'build/',
				src: ['**/*'],
				dest: 'cakifo/'
			}
		}
	});

	// Default task
	grunt.registerTask('default', [ 'watch' ]);

	// Pre-build task
	grunt.registerTask( 'pre-build', [ 'version', 'less', 'sass', 'imagemin' ]);

	// Build task
	grunt.registerTask( 'build', [ 'clean:build', 'copy:build', 'cssmin', 'compress:build' ]);

};
