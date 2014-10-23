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
				desiredCapabilities: {
					browserName: 'phantomjs'
				},
				reporter: 'mocha-teamcity-reporter'
			},
			test: {
				tests: './tests/releases/**/*.js'
			}
		}
	});

	grunt.registerTask('build', ['shell:build']);
	grunt.registerTask('default', ['build']);

};