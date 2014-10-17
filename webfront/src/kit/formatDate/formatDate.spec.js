define(function(require, exports, module) {
    //requirements
    var formatDate = require('./formatDate');

    describe(module.id, function() {
        var time = 1410184512307;

        it('will null without date', function() {
            expect(formatDate()).toEqual(null);
        });

        it('will right result', function() {
            expect(formatDate(time)).toEqual('08.09.2014');
        });

        it('will right result with redefined format', function() {
            expect(formatDate(time, { format: 'DD-MM-YYYY' })).toEqual('08-09-2014');
        });
    });
});