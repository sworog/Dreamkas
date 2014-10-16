define(function(require, exports, module) {
    //requirements
    var normalizeNumber = require('./normalizeNumber');

    describe(module.id, function() {
        var normalizedResult = normalizeNumber(' 3 , 555 ');

        it('normalized result is number', function() {

            expect(typeof normalizedResult).toEqual('number');
        });

        it('normalized number is correct', function() {

            expect(normalizedResult).toEqual(3.555);
        });

    });
});