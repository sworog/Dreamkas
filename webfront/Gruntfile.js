module.exports = function(grunt) {

	grunt.loadNpmTasks('grunt-shell');

	grunt.initConfig({
		shell: {
			build: {
				command: 'rm -rf build && node tools/r.js -o tools/buildConfig.js'
			}
		}
	});

	grunt.registerTask('build', ['shell:build']);

	grunt.registerTask('default', ['build']);

};