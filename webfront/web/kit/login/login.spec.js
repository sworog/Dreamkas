define(function(require, exports, module) {
    //requirements
    var login = require('./login');
    var cookies = require('cookies');
    var location = require('kit/location/location');

    describe(module.id, function() {
        var token = 'test_token';

        it('will right cookie', function() {
            spyOn(location, 'redirect');
            spyOn(location, 'reload');

            login(token);

            expect(cookies.get('token')).toEqual(token);
        });

        it('will right redirect when login page opened', function() {
            var redirectUri;

            spyOn(location, 'isContain').and.callFake(function(string)
            {
                return string == 'login';
            });

            spyOn(location, 'redirect').and.callFake(function(href)
            {
                redirectUri = href;
            });

            login(token);

            expect(redirectUri).toEqual('/');
        });

        it('will reload when not login page opened', function() {
            var redirectUri;

            spyOn(location, 'isContain').and.callFake(function(string)
            {
                return string != 'login';
            });

            spyOn(location, 'reload');

            login(token);

            expect(location.reload).toHaveBeenCalled();
        });
    });
});