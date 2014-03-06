define(function(require, exports, module) {
    //requirements
    var formatMoney = require('./formatMoney'),
        numeral = require('numeral');

    numeral.language('root', require('nls/root/numeral'));

    describe('utils/formatMoney', function() {
        describe('root locale format', function(){

            numeral.language('root');

            it('format integer number', function(){
                expect(formatMoney(12)).toEqual('12,00');
            });

            it('format float number', function(){
                expect(formatMoney(12.45)).toEqual('12,45');
            });

            it('format long float number', function(){
                expect(formatMoney(12.451234)).toEqual('12,45');
            });

            it('format short float number', function(){
                expect(formatMoney(12.4)).toEqual('12,40');
            });
        });
    });
});