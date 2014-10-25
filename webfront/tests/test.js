var expect = require('chai').expect,
    browser = require('./browser');

describe('Initial tests', function () {

    it('title is ok', function(done) {

        browser
            .url(host)
            .getTitle(function(err, value){
                expect(value).to.equal('Dreamkas');
            })
            .end(done);

    });

});