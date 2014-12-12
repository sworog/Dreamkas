var webdriverio = require('webdriverio'),
    options = {
        desiredCapabilities: {
            browserName: 'chrome'
        }
    };

var resemble = require('node-resemble-js');

var fs = require('fs');

var expect = require('chai').expect;

if (process.env.seleniumHost) {
    options.host = process.env.seleniumHost;
}

if (process.env.seleniumPort) {
    options.port = process.env.seleniumPort;
}

var browser = webdriverio
    .remote(options)
    .init();

var diffDir = 'test/diff/',
    screenshotDir = 'test/screenshots/';

fs.exists(diffDir, function(exists){
    !exists && fs.mkdirSync(diffDir);
});

fs.exists(screenshotDir, function(exists){
    !exists && fs.mkdirSync(screenshotDir);
});

browser.addCommand("diff", function(screen, cb) {

    var diffPath = 'test/diff/' + screen + '.png',
        originPath = 'test/screenshots/' + screen + '.png',
        failPath = 'test/fails/' + screen + '.png';

    fs.exists(originPath, function(exists) {

        browser.saveScreenshot(diffPath, function(err) {

            if (exists){
                resemble(diffPath).compareTo(originPath).onComplete(function(data) {
                    console.log(data);

                    if (data.misMatchPercentage < 0.1){
                        fs.unlinkSync(diffPath)
                    }

                    expect(data.misMatchPercentage).to.be.below(0.1);

                    cb(err, data);
                });
            } else {
                console.log(diffPath + ' is a new screenshot.');
                browser.saveScreenshot(originPath, function(err) {
                    cb(err);
                });
            }

        });
    });

});

after(function(done) {
    browser.end(done);
});

module.exports = browser;