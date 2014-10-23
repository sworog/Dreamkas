var browser = require('../../browser');

var expect = require('chai').expect;

describe('test', function () {

    it('body status', function(done) {

        browser
            .url('http://borovin.lighthouse.pro')
            .waitForExist('body[status="loaded"]', 5000, function(){

                browser.getAttribute('body', 'status', function(error, attr){
                    expect(attr).to.have.string('loaded')
                });

            })
            .end(done);

    });

});