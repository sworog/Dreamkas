define(function(require, exports, module) {
    //requirements
    var formatInventory = require('./formatInventory'),
        numeral = require('libs/numeral');

    numeral.language('root', require('nls/root/numeral'));

    describe('utils/formatInventory', function() {
        describe('root locale format', function(){

            numeral.language('root');

            it('format integer number', function(){
                expect(formatInventory(12)).toEqual('12,0');
            });

            it('format float number', function(){
                expect(formatInventory(12.453)).toEqual('12,453');
            });

            it('format long float number', function(){
                expect(formatInventory(12.451234)).toEqual('12,451');
            });

            it('format short float number', function(){
                expect(formatInventory(12.4)).toEqual('12,4');
            });

            it('format float number with zero', function(){
                expect(formatInventory(12.40)).toEqual('12,4');
                expect(formatInventory(12.440)).toEqual('12,44');
            });

            it('format float number with double zero', function(){
                expect(formatInventory(12.400)).toEqual('12,4');
            });
        });
    });
});