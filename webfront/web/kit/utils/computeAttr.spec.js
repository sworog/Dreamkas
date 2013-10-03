define(function(require) {
    //requirements
    var Model = require('../core/model'),
        computeAttr = require('./computeAttr');

    require('lodash');

    describe('utils/computeAttr', function() {

        var model;

        beforeEach(function() {
            model = new Model({
                a: 1,
                b: 2,
                computedAttr: computeAttr(['a', 'b'], function(a, b) {
                    return a + b;
                })
            })
        });

        it('computed attribute in model', function(){

            expect(model.get('computedAttr')).toEqual(3);

        });

        it('computed attribute change', function(){

            model.set('a', 5);

            expect(model.get('computedAttr')).toEqual(7);

        });

        it('computed attribute trigger', function(){

            var handler = jasmine.createSpy(),
                options = {
                    test: true
                };

            model.listenTo(model, {
                'change:computedAttr': handler
            });

            model.set('a', 5, options);
            model.set('c', 8);

            expect(handler).toHaveBeenCalledWith(model, 7, options);
            expect(handler.calls.length).toEqual(1);

            model.set({
                a: 3,
                b: 1
            }, options);

            model.set({
                c: 5,
                d: 3
            });

            expect(handler).toHaveBeenCalledWith(model, 4, options);
            expect(handler.calls.length).toEqual(2);

        });

    });
});