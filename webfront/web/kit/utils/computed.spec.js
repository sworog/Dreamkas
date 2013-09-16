define(function(require) {
    //requirements
    var getter = require('../mixins/getter.js'),
        _ = require('underscore'),
        computed = require('./computed.js'),
        objectMock = require('../mocks/object.js');

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