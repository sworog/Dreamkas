module.exports = function() {

    this.World = function World(callback) {

        var webdriverio = require('webdriverio'),
            options = {
                desiredCapabilities: {
                    browserName: 'chrome'
                }
            };

        if (process.env.seleniumHost){
            options.host = process.env.seleniumHost;
        }

        if (process.env.seleniumPort){
            options.port = process.env.seleniumPort;
        }

        this.browser = webdriverio
            .remote(options);

        callback();
    };
};
