define(function(require, exports, module) {
    //requirements
    var checkKey = require('./checkKey');

    describe(module.id, function(){

        it('check one key by keycode', function(){

            expect(checkKey(8, ['BACKSPACE'])).toBeTruthy();

        });

        it('check multiple key by keycode', function(){

            expect(checkKey(8, ['UP', 'BACKSPACE', 'DOWN'])).toBeTruthy();

        });

        it('fail one key check', function(){

            expect(checkKey(8, ['UP'])).toBeFalsy();

        });

        it('fail multiple key check', function(){

            expect(checkKey(8, ['UP', 'DOWN', 'ESC'])).toBeFalsy();

        });

    });
});