var browser = require('webdriverio').remote({
    desiredCapabilities: {
        // You may choose other browsers
        // http://code.google.com/p/selenium/wiki/DesiredCapabilities
        browserName: 'chrome'
    }
    // webdriverjs has a lot of output which is generally useless
    // However, if anything goes wrong, remove this to see more details
    //logLevel: 'silent'
});

browser.init();


var expect = require('chai').expect;

describe('test', function () {

    it('body status', function(done) {

        browser
            .url('http://google.com')
            .waitForExist('body[status="loaded"]', function(){

                browser.getAttribute('body', 'status', function(error, attr){
                    expect(attr).to.have.string('loaded')
                });
            })
            .end(done);

    });

});