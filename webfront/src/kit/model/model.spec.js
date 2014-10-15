define(function(require, exports, module) {
    //requirements
    var Model = require('./model');

    describe(module.id, function() {

        var getModelParams = function() {
            return { a: 1, b: 2, c: 'text' };
        };

        var getModelDeepParams = function() {
            return { a: { b: { c: 'text' } } };
        };

        it('toJSON without options', function() {

            var model = new Model(getModelParams());

            expect(model.toJSON()).toEqual(getModelParams());
        });

        it('toJSON with saveData array', function() {

            var CustomModel = Model.extend({
                saveData: [ 'c' ]
            });

            var model = new CustomModel(getModelParams());

            expect(model.toJSON({ isSave: true })).toEqual({ c: 'text' });
        });

        it('toJSON with saveData function', function() {

            var CustomModel = Model.extend({
                saveData: function()
                {
                    return getModelParams();
                }
            });

            var model = new CustomModel();

            expect(model.toJSON({ isSave: true })).toEqual(model.saveData());
        });

        it('get attributes', function() {

            var model = new Model(getModelDeepParams());

            expect(model.get('a')).toEqual(getModelDeepParams().a);
            expect(model.get('a.b.c')).toEqual(getModelDeepParams().a.b.c);
        });

    });
});