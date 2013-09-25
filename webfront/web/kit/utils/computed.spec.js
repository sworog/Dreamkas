define(function(require) {
    //requirements
    var getter = require('../mixins/getter'),
        _ = require('lodash'),
        computed = require('./computed'),
        objectMock = require('../mocks/object');

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