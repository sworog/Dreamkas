var expect = require('chai').expect;

describe('Store page tests', function() {

    beforeEach(function(done){
        browser
            .url(host + '/pages/stores')
            .waitFor('body[status="loaded"]', 5000, done)
    });

    it('Store modal page', function(done) {

        browser
            .click('.store__link')
            .waitForVisible('.modal_store')
            .saveScreenshot('snapshot3.png')
            .call(done);

    });

    it('Store page', function(done) {

        browser
            .saveScreenshot('snapshot4.png')
            .call(done);

    });

});