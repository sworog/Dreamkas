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

var browser = webdriverio
    .remote(options)
    .init();

after(function(done) {
    browser.end(done);
});

module.exports = browser;