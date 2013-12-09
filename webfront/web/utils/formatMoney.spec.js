define(function(require, exports, module) {
    //requirements
    var formatMoney = require('./formatMoney'),
        numeral = require('libs/numeral');

    numeral.language('root', require('nls/root/numeral'));

    describe('utils/formatMoney', function() {
        describe('root locale format', function(){

            numeral.language('root');

            it('format integer number', function(){
                expect(formatMoney('12')).toEqual('12,00');
            });
        });
    });
});