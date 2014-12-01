var expect = require('chai').expect;

describe('Initial tests', function() {

    it('main page', function(done) {

        browser
            .url(host)
            .execute(function() {
                require.config({
                    shim: {
                        app: [
                            'resources/currentUser/mocks/get',
                            'resources/store/mocks/get_3'
                        ]
                    }
                });
            })
            .waitFor('body[status="loaded"]', function() {
                browser.saveScreenshot('snapshot.png');
                done();
            });

    });

});