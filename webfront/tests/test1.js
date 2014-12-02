var expect = require('chai').expect;

describe.only('Store page tests', function() {

    beforeEach(function(done){
        browser
            .url(host + '/pages/stores')
            .waitFor('body[status="loaded"]', 5000, done)
    });

    it('Store modal page', function(done) {

        browser
            .click('.store__link')
            .waitForVisible('.modal_store')
            .saveScreenshot('snapshot1.png')
            .call(done);

    });

    it('Store page', function(done) {

        browser
            .saveScreenshot('snapshot2.png')
            .call(done);

    });

});