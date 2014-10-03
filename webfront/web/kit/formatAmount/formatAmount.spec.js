define(function(require, exports, module) {
    //requirements
    var formatAmount = require('./formatAmount');

    describe(module.id, function() {

        it('will right format', function() {
            expect(formatAmount(1)).toEqual('1,0');
            expect(formatAmount(1000)).toEqual('1 000,0');
            expect(formatAmount(1000000)).toEqual('1 000 000,0');

            expect(formatAmount(1.2)).toEqual('1,2');
            expect(formatAmount(1.22)).toEqual('1,22');
            expect(formatAmount(1.223)).toEqual('1,223');
            expect(formatAmount(1.223555)).toEqual('1,224');

            expect(formatAmount(1000.223)).toEqual('1 000,223');
        });
    });
});