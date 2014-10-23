var expect = require('chai').expect,
    browser = require('./browser');

describe('test', function () {

    it('body status', function(done) {

        browser
            .url(host)
            .waitForExist('body[status="loaded"]', 5000, function(){

                browser.getAttribute('body', 'status', function(error, attr){
                    expect(attr).to.have.string('loaded')
                });

            })
            .end(done);

    });

});