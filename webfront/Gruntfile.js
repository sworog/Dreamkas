module.exports = function(grunt) {

	grunt.initConfig({
		exec: {
			build: 'node tools/r.js -o tools/build.js',
			remove_build: 'rm -rf build'
		}
	});

	grunt.loadNpmTasks('grunt-exec');

	grunt.registerTask('default', ['exec:remove_build', 'exec:build']);
};