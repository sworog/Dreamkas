define(function(require, exports, module) {
    //requirements
    var logout = require('./logout');
    var cookies = require('cookies');
    var location = require('kit/location/location');

    describe(module.id, function() {

        it('will right cookie', function() {
            spyOn(location, 'redirect');

            logout();

            expect(cookies.get('token')).toEqual('');
        });

        it('will right redirect', function() {
            var redirectUri;

            spyOn(location, 'redirect').and.callFake(function(href)
            {
                redirectUri = href;
            });

            logout();

            expect(redirectUri).toEqual('/');
        });
    });
});