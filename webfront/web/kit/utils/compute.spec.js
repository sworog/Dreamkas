define(function(require) {
    //requirements
    var getter = require('./getter'),
        _ = require('lodash'),
        compute = require('./compute');

    var object = _.extend({
        computedString: compute(['string', 'number'], function(string, number){
            return string + '+' + number;
        })
    }, require('../fixtures/object'), getter);

    describe('utils/compute', function() {
        it('computed get method', function() {
            expect(object.get('computedString')).toEqual('test string level 1+1');
        });
    });
});