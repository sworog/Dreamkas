module.exports = function(grunt) {

	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-webdriver');

	grunt.initConfig({
		shell: {
			build: {
				command: 'rm -rf build && node tools/r.js -o tools/buildConfig.js'
			}
		},
		webdriver: {
			options: {
				//webdriverio options (https://github.com/webdriverio/webdriverio#options)
				desiredCapabilities: {
					browserName: 'phantomjs'
				},
				//logLevel: 'verbose',
				screenshotPath: 'tests/screenshots/',

				//grunt-webdriver mocha options (https://github.com/webdriverio/grunt-webdriver#options)
				reporter: '<%= grunt.option("reporter") %>'
			},
			test: {
				tests: './tests/*.js'
			}
		}
	});

	grunt.registerTask('build', ['shell:build']);

	grunt.registerTask('test', function(){
		var host = grunt.option('host');

		if (!host){
			grunt.warn('Host must be specified (--host=hostName).');
		}

		grunt.log.ok('Tests start on host ' + host);

		global['host'] = host;
		grunt.task.run('webdriver');
	});

	grunt.registerTask('default', ['build']);

};