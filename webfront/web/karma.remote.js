// Karma configuration
// Generated on Fri Sep 27 2013 16:37:01 GMT+0400 (MSK)

module.exports = function(config) {

    config.set({

        // base path, that will be used to resolve files and exclude
        basePath: '',

        // frameworks to use
        frameworks: ['jasmine', 'requirejs'],

        // list of files / patterns to load in the browser
        files: [
            'karma.main.js',
            {pattern: '**/*.js', included: false},
            {pattern: '**/*.ejs', included: false}
        ],

        // list of files to exclude
        exclude: [
            'karma.conf.js',
            'karma.remote.js',
            'node_modules/**/*.spec.js',
            'bower_components/**/*.spec.js'
        ],

        // test results reporter to use
        // possible values: 'dots', 'progress', 'junit', 'growl', 'coverage'
        reporters: ['dots'],

        allureReport: {
            reportDir: 'build/allure-report'
        },

        // enable / disable colors in the output (reporters and logs)
        colors: true,

        // level of logging
        // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
        logLevel: config.LOG_INFO,

        // enable / disable watching file and executing tests whenever any file changes
        autoWatch: false,

        hostname: require("os").hostname(),

        customLaunchers: {
            'Remote-Firefox': {
                base: 'WebdriverJS',
                config: {
                    host: 'selenium.lighthouse.pro',
                    port: 80,
                    desiredCapabilities: {
                        browserName: 'firefox'
                    }
                }
            },
            'Remote-Chrome': {
                base: 'WebdriverJS',
                config: {
                    host: 'selenium.lighthouse.pro',
                    port: 80,
                    desiredCapabilities: {
                        browserName: 'chrome'
                    }
                }
            }
        },

        // Start these browsers, currently available:
        // - Chrome
        // - ChromeCanary
        // - Firefox
        // - Opera
        // - Safari (only Mac)
        // - PhantomJS
        // - IE (only Windows)
        browsers: ['Remote-Firefox', 'Remote-Chrome'],

        // If browser does not capture in given timeout [ms], kill it
        captureTimeout: 60000,

        // Continuous Integration mode
        // if true, it capture browsers, run tests and exit
        singleRun: true
    });
};
