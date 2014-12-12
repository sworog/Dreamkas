var expect = require('chai').expect,
    browser = require('../tools/browser');

describe('Store page with stores', function() {

    this.timeout(10000);

    beforeEach(function(done) {

        browser
            .url(process.env.host + '/test.html')
            .execute(function(){
                require([
                    'app',
                    'resources/currentUser/mocks/get',
                    'resources/store/mocks/get_3'
                ], function(app){
                    app.start('/stores');
                });
            })
            .waitFor('body[status="loaded"]', 5000, done)
    });
    
    it.only('Store modal', function(done) {

        browser
            .click('.store__link')
            .waitForVisible('.modal_store')
            .diff('test')
            .call(done);

    });

});