var webdriverio = require('webdriverio'),
    options = {desiredCapabilities: {browserName: 'chrome'}},
    expect = require('chai').expect;

var browser = webdriverio
    .remote(options)
    .init();

after(function(done){
    browser.end(done);
});

module.exports = browser;