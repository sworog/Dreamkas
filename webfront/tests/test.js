var expect = require('chai').expect;

describe('Initial tests', function() {

    //it('main page', function(done) {
    //
    //    browser
    //        .url(host)
    //        .execute(function() {
    //            require({
    //                deps: [
    //                    'resources/currentUser/mocks/get'
    //                ]
    //            });
    //        })
    //        .getTitle(function(err, value) {
    //            expect(value).to.equal('Dreamkas');
    //        })
    //        .waitFor('body[status="loaded"]', function() {
    //            browser.saveScreenshot('snapshot1.png');
    //        })
    //        .call(done);
    //
    //});

    it('stores page', function(done) {

        browser
            .url(host + '/stores')
            .execute(function() {
                require({
                    deps: [
                        'resources/currentUser/mocks/get',
                        'resources/store/mocks/get_3'
                    ]
                });
            })
            .getTitle(function(err, value) {
                expect(value).to.equal('Dreamkas');
            })
            .waitFor('body[status="loaded"]', function() {
                browser.saveScreenshot('snapshot2.png');
                done();
            });

    });

});