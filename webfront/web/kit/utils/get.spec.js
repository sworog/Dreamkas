define(function(require) {
    //requirements
    var get = require('./get'),
        objectFixture = require('../fixtures/object');

    require('lodash');

    describe('utils/get', function(){

        it('get method', function() {

            var object = _.extend({
                get: get
            }, objectFixture);

            expect(object.get('string')).toEqual('test string level 1');
            expect(object.get('number')).toEqual(1);
            expect(object.get('array')).toEqual(['a1 level 1', 'a2 level 1', 'a3 level 1', 'a4 level 1']);
            expect(object.get('bool')).toEqual(false);
            expect(object.get('func')).toEqual('test string level 11');
            expect(object.get('undefinedValue')).toEqual(undefined);

            expect(object.get('object.object.object.string')).toEqual('test string level 4');
            expect(object.get('object.object.object.number')).toEqual(4);
            expect(object.get('object.object.object.array')).toEqual(['a1 level 4', 'a2 level 4', 'a3 level 4', 'a4 level 4']);
            expect(object.get('object.object.object.bool')).toEqual(true);
            expect(object.get('object.object.object.func')).toEqual('test string level 11');
            expect(object.get('object.object.object.undefinedValue')).toEqual(undefined);
            expect(object.get('object.undefinedValue.object')).toEqual(undefined);

            expect(object.get('value')).toEqual('test string level 11');

        });
    });
});