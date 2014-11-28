// Karma configuration
// Generated on Fri Sep 27 2013 16:37:01 GMT+0400 (MSK)

module.exports = function(config) {
    config.set({

        // base path, that will be used to resolve files and exclude
        basePath: '../src',

        // frameworks to use
        frameworks: ['jasmine', 'requirejs'],

        // list of files / patterns to load in the browser
        files: [
            'polyfills.js',
            '../tools/karma.main.js',
            'polyfills.js',
            'bower_components/jquery/dist/jquery.min.js',
            {pattern: '**/*.js', included: false},
            {pattern: '**/*.ejs', included: false}
        ],

        // list of files to exclude
        exclude: [
            'karma.conf.js',
            'node_modules/**/*.spec.js',
            'bower_components/**/*.spec.js'
        ],


        // test results reporter to use
        // possible values: 'dots', 'progress', 'junit', 'growl', 'coverage'
        reporters: ['dots'],

        coverageReporter: {
            reporters:[
                {type: 'html', dir:'../artifacts/coverage/'},
                {type: 'teamcity'}
            ]
        },

        preprocessors: {
            // source files, that you wanna generate coverage for
            // do not include tests or libraries
            // (these files will be instrumented by Istanbul),
            'blocks/**/!(*.spec.js)*.js': 'coverage',
            'kit/**/!(*.spec.js)*.js': 'coverage'
        },

        // web server port
        port: 9876,


        // enable / disable colors in the output (reporters and logs)
        colors: true,


        // level of logging
        // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
        logLevel: config.LOG_INFO,


        // enable / disable watching file and executing tests whenever any file changes
        autoWatch: false,


        // Start these browsers, currently available:
        // - Chrome
        // - ChromeCanary
        // - Firefox
        // - Opera
        // - Safari (only Mac)
        // - PhantomJS
        // - IE (only Windows)
        browsers: ['PhantomJS'],


        // If browser does not capture in given timeout [ms], kill it
        captureTimeout: 60000,


        // Continuous Integration mode
        // if true, it capture browsers, run tests and exit
        singleRun: true
    });
};
