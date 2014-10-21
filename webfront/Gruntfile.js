module.exports = function(grunt) {

	grunt.loadNpmTasks('grunt-shell');

	grunt.initConfig({
		shell: {
			build: {
				command: 'node tools/r.js -o tools/buildConfig.js'
			},
			remove_build: {
				command: 'rm -rf build'
			}
		}
	});

	grunt.registerTask('build', ['shell:remove_build', 'shell:build']);
	grunt.registerTask('default', ['build']);

};