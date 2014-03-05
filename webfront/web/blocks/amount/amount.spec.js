define(function(require, exports, module) {
    //requirements
    var numeral = require('numeral');

    var templates = {
        amount: require('tpl!./amount.html')
    };

    require('jquery');

    numeral.language('root', require('nls/root/numeral'));

    describe('blocks/amount', function(){
        describe('root locale', function(){

            numeral.language('root');

            it('integer number in template', function(){
                var $inventory = $(templates.amount({value: 123000000}));

                expect($.trim($inventory.text())).toEqual('123 000 000,0');
            });

            it('number with decimals in template', function(){
                var $inventory = $(templates.amount({value: 123.4}));

                expect($.trim($inventory.text())).toEqual('123,4');
            });

            it('negative number with decimals in template', function(){
                var $inventory = $(templates.amount({value: -123.4}));

                expect($.trim($inventory.text())).toEqual('-123,4');
            });

            it('number with hundredths in template', function(){
                var $inventory = $(templates.amount({value: 123.45}));

                expect($.trim($inventory.text())).toEqual('123,45');
            });

            it('number with thousandths in template', function(){
                var $inventory = $(templates.amount({value: 123.456}));

                expect($.trim($inventory.text())).toEqual('123,456');
            });

            it('number with ten-thousandths in template', function(){
                var $inventory = $(templates.amount({value: 123.4561}));

                expect($.trim($inventory.text())).toEqual('123,456');
            });

            it('int part in number does not change', function(){
                var $inventory = $(templates.amount({value: 123.6}));

                expect($.trim($inventory.text())).toEqual('123,6');
            });
        });
    });
});