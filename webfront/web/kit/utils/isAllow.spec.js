define(function(require) {
    //requirements
    var isAllow = require('./isAllow');

    require('lodash');

    describe('utils/isAllow', function(){

        var permissions = {};

        beforeEach(function(){
            permissions = {
                fullAccessResource: 'all',
                getAccessResource: 'GET',
                partialAccessResource: ['GET', 'POST']
            }
        });

        it('isAllow does not affect permissions object', function(){

            var originalPermissions = _.clone(permissions);

            isAllow(permissions, 'fullAccessResource', 'GET');
            isAllow(permissions, 'getAccessResource', 'GET');
            isAllow(permissions, 'getAccessResource', 'POST');
            isAllow(permissions, 'partialAccessResource', 'GET');

            expect(originalPermissions).toEqual(permissions);
        });

        it('isAllow GET method in fullAccessResource', function(){
            expect(isAllow(permissions, 'fullAccessResource', 'GET')).toBeTruthy();
        });

        it('isAllow GET method in getAccessResource', function(){
            expect(isAllow(permissions, 'getAccessResource', 'GET')).toBeTruthy();
        });

        it('isAllow POST method in getAccessResource', function(){
            expect(isAllow(permissions, 'getAccessResource', 'POST')).toBeFalsy();
        });

        it('isAllow GET method in partialAccessResource', function(){
            expect(isAllow(permissions, 'partialAccessResource', 'GET')).toBeTruthy();
        });

        it('isAllow POST method in partialAccessResource', function(){
            expect(isAllow(permissions, 'partialAccessResource', 'POST')).toBeTruthy();
        });

        it('isAllow PUT method in partialAccessResource', function(){
            expect(isAllow(permissions, 'partialAccessResource', 'PUT')).toBeFalsy();
        });
    });
});