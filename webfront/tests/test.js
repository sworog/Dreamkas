var expect = require('chai').expect,
    browser = require('./browser');

describe('test', function () {

    it('body status', function(done) {

        browser
            .url(host)
            .waitForExist('body[status="loaded"]', 10000, function(){

                browser.getAttribute('body', 'status', function(error, attr){
                    browser.saveScreenshot('artifacts/test.png');
                    expect(attr).to.have.string('loaded')
                });

            })
            .end(done);

    });

});