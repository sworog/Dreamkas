define(function(require) {
    //requirements
    var get = require('./get'),
        objectFixture = require('../fixtures/object');

    require('lodash');

    describe('utils/get', function(){

        it('as function', function(){

            var object = _.cloneDeep(objectFixture);

            expect(get(object, 'string')).toEqual('test string level 1');
            expect(get(object, 'number')).toEqual(1);
            expect(get(object, 'array')).toEqual(['a1 level 1', 'a2 level 1', 'a3 level 1', 'a4 level 1']);
            expect(get(object, 'bool')).toEqual(false);
            expect(get(object, 'func')).toEqual('test string level 11');
            expect(get(object, 'func', 2)).toEqual('test string level 112');
            expect(get(object, 'func', 2, 3)).toEqual('test string level 1123');
            expect(get(object, 'undefinedValue')).toEqual(undefined);

            expect(get(object, 'object.object.object.string')).toEqual('test string level 4');
            expect(get(object, 'object.object.object.number')).toEqual(4);
            expect(get(object, 'object.object.object.array')).toEqual(['a1 level 4', 'a2 level 4', 'a3 level 4', 'a4 level 4']);
            expect(get(object, 'object.object.object.bool')).toEqual(true);
            expect(get(object, 'object.object.object.func')).toEqual('test string level 44');
            expect(get(object, 'object.object.object.func', 5)).toEqual('test string level 445');
            expect(get(object, 'object.object.object.func', 5, 6)).toEqual('test string level 4456');
            expect(get(object, 'object.object.object.undefinedValue')).toEqual(undefined);
            expect(get(object, 'object.undefinedValue.object.undefinedValue')).toEqual(undefined);

            expect(get(object, 'value')).toEqual('test string level 11');
            expect(get(object, 'value', 2)).toEqual('test string level 112');
            expect(get(object, 'value', 2, 3)).toEqual('test string level 1123');

        });

        it('as method', function() {

            var object = _.extend({
                get: get
            }, objectFixture);

            expect(object.get('string')).toEqual('test string level 1');
            expect(object.get('number')).toEqual(1);
            expect(object.get('array')).toEqual(['a1 level 1', 'a2 level 1', 'a3 level 1', 'a4 level 1']);
            expect(object.get('bool')).toEqual(false);
            expect(object.get('func')).toEqual('test string level 11');
            expect(object.get('func', 2)).toEqual('test string level 112');
            expect(object.get('func', 2, 3)).toEqual('test string level 1123');
            expect(object.get('undefinedValue')).toEqual(undefined);

            expect(object.get('object.object.object.string')).toEqual('test string level 4');
            expect(object.get('object.object.object.number')).toEqual(4);
            expect(object.get('object.object.object.array')).toEqual(['a1 level 4', 'a2 level 4', 'a3 level 4', 'a4 level 4']);
            expect(object.get('object.object.object.bool')).toEqual(true);
            expect(object.get('object.object.object.func')).toEqual('test string level 44');
            expect(object.get('object.object.object.func', 5)).toEqual('test string level 445');
            expect(object.get('object.object.object.func', 5, 6)).toEqual('test string level 4456');
            expect(object.get('object.object.object.undefinedValue')).toEqual(undefined);
            expect(object.get('object.undefinedValue.object.undefinedValue')).toEqual(undefined);

            expect(object.get('value')).toEqual('test string level 11');
            expect(object.get('value', 2)).toEqual('test string level 112');
            expect(object.get('value', 2, 3)).toEqual('test string level 1123');

        });
    });
});