var webdriverio = require('webdriverio'),
    options = {
        host: 'selenium.lighthouse.pro',
        port: 80,
        desiredCapabilities: {
            browserName: 'chrome'
        }
    };

var browser = webdriverio
    .remote(options)
    .init();

after(function(done) {
    browser.end(done);
});

module.exports = browser;