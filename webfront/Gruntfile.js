module.exports = function(grunt) {

	grunt.loadNpmTasks('grunt-shell');
	grunt.loadNpmTasks('grunt-casper');

	grunt.initConfig({
		shell: {
			build: {
				command: 'node tools/r.js -o tools/buildConfig.js'
			},
			remove_build: {
				command: 'rm -rf build'
			}
		},
		casper: {
			test : {
				options : {
					//test : true
				},
				files : {
					'xunit/casper-results.xml' : ['tests/test.js']
				}
			}
		}
	});

	grunt.registerTask('build', ['shell:remove_build', 'shell:build']);
	grunt.registerTask('default', ['build']);

};