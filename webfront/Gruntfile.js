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
			options : {
				test : true,
				parallel : false,
				concurrency : 10
			},
			task : {
				src: [grunt.option('test') || 'tests/releases'],
				dest: ['xunit/casper-report.xml']
			}
		}
	});

	grunt.registerTask('build', ['shell:remove_build', 'shell:build']);
	grunt.registerTask('default', ['build']);

};