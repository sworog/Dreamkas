define(function(require, exports, module) {
    //requirements
    var isAllow = require('./isAllow');

    require('lodash');

    describe(module.id, function(){

        var permissions = {};

        beforeEach(function(){
            permissions = {
                fullAccessResource: 'all',
                getAccessResource: 'GET',
                getPostAccessResource: ['GET', 'POST']
            }
        });

        it('isAllow does not affect permissions object', function(){

            var originalPermissions = _.clone(permissions);

            isAllow(permissions, 'fullAccessResource', 'GET');
            isAllow(permissions, 'getAccessResource', 'GET');
            isAllow(permissions, 'getAccessResource', 'POST');
            isAllow(permissions, 'getPostAccessResource', 'GET');

            expect(originalPermissions).toEqual(permissions);
        });

        it('GET method in fullAccessResource is allowed', function(){
            expect(isAllow(permissions, 'fullAccessResource', 'GET')).toBeTruthy();
        });

        it('GET method in fullAccessResource without method argument is allowed', function(){
            expect(isAllow(permissions, 'fullAccessResource')).toBeTruthy();
        });

        it('GET method in getAccessResource is allowed', function(){
            expect(isAllow(permissions, 'getAccessResource', 'GET')).toBeTruthy();
        });

        it('POST method in getAccessResource is not allowed', function(){
            expect(isAllow(permissions, 'getAccessResource', 'POST')).toBeFalsy();
        });

        it('GET method in getPostAccessResource is allowed', function(){
            expect(isAllow(permissions, 'getPostAccessResource', 'GET')).toBeTruthy();
        });

        it('POST method in getPostAccessResource is allowed', function(){
            expect(isAllow(permissions, 'getPostAccessResource', 'POST')).toBeTruthy();
        });

        it('PUT method in getPostAccessResource is not allowed', function(){
            expect(isAllow(permissions, 'getPostAccessResource', 'PUT')).toBeFalsy();
        });

        it('GET and POST in getPostAccessResource is allowed', function(){
            expect(isAllow(permissions, 'getPostAccessResource', ['GET', 'POST'])).toBeTruthy();
        });

        it('PUT and POST in getPostAccessResource is not allowed', function(){
            expect(isAllow(permissions, 'getPostAccessResource', ['PUT', 'POST'])).toBeFalsy();
        });

        it('GET and POST in fullAccessResource is allowed', function(){
            expect(isAllow(permissions, 'fullAccessResource', ['GET', 'POST'])).toBeTruthy();
        });

        it('GET and POST in getAccessResource', function(){
            expect(isAllow(permissions, 'getAccessResource', ['GET', 'POST'])).toBeFalsy();
        });
    });
});