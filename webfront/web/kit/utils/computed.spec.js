define(function(require) {
    //requirements
    var getter = require('kit/utils/getter'),
        _ = require('underscore'),
        computed = require('kit/utils/computed'),
        objectMock = require('kit/mocks/object');

    var object = _.extend({
        computedString: computed(['string', 'number'], function(string, number){
            return string + '+' + number;
        })
    }, objectMock, getter);

    describe('computed', function() {
        it('computed get method', function() {
            expect(object.get('computedString')).toEqual('test string level 1+1');
        });
    });
});