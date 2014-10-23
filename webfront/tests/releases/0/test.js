var expect = require('chai').expect;

describe('test', function () {

    it('body status', function(done) {

        browser
            .setViewportSize({
                width: 1024,
                height: 800
            })
            .url('http://lighthouse.dev')
            .waitForExist('body[status="loaded"]', 5000, function(){

                browser.getAttribute('body', 'status', function(error, attr){
                    expect(attr).to.have.string('loaded')
                });

                browser.saveScreenshot('test.png');

            })
            .end(done);

    });

});