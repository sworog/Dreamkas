define(function(require) {
    //requirements
    var Model = require('kit/model');

    var model = new Model({
        string: 'test',
        a: {
            b: {
                c: 'test'
            }
        }
    });

    describe('Lighthouse model', function() {
        it('get method', function() {
            expect(model.get('string')).toEqual('test');
            expect(model.get('a.b.c')).toEqual('test');
            expect(model.get('c.a.b')).toEqual(undefined);
        });
    });

});