var expect = require('chai').expect;

describe('test', function () {

    it('body status', function(done) {

        browser
            .url('http://lighthouse.dev')
            .waitForExist('body[status="loaded"]', function(){

                browser.getAttribute('body', 'status', function(error, attr){
                    expect(attr).to.have.string('loaded')
                });
            })
            .end(done);

    });

});