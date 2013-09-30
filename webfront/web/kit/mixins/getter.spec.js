define(function(require) {
    //requirements
    var getter = require('./getter'),
        _ = require('lodash'),
        objectMock = require('../mocks/object');

    var object = _.extend({
        a: {
            b: {
                func: function(){
                    return this.string + this.number
                }
            }
        }
    }, objectMock, getter);

    describe('getter', function() {
        it('get method', function() {
            expect(object.get('string')).toEqual('test string level 1');
            expect(object.get('number')).toEqual(1);
            expect(object.get('array')).toEqual(['a1 level 1', 'a2 level 1', 'a3 level 1', 'a4 level 1']);
            expect(object.get('bool')).toEqual(false);
            expect(object.get('func')).toEqual('test function level 1');
            expect(object.get('value')).toEqual(undefined);

            expect(object.get('object.object.object.string')).toEqual('test string level 4');
            expect(object.get('object.object.object.number')).toEqual(4);
            expect(object.get('object.object.object.array')).toEqual(['a1 level 4', 'a2 level 4', 'a3 level 4', 'a4 level 4']);
            expect(object.get('object.object.object.bool')).toEqual(true);
            expect(object.get('object.object.object.func')).toEqual('test function level 4');
            expect(object.get('object.object.object.value')).toEqual(undefined);
            expect(object.get('object.value.object.value')).toEqual(undefined);

            expect(object.get('a.b.func')).toEqual('test string level 11');
        });
    });
});