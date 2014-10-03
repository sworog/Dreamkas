define(function(require, exports, module) {
    //requirements
    var formatDateTime = require('./formatDateTime');

    describe(module.id, function() {

        it('will right result', function() {
            expect(formatDateTime(1410185991644)).toEqual('08.09.2014 18:19');
        });
    });
});