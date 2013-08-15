define(function(require) {
    //requirements
    var Model = require('kit/model');

    var model = new Model();

    describe('Lighthouse model specs', function() {
        it('Model fetch method', function() {
            expect(typeof model.fetch).toEqual('function');
        });
    });

});