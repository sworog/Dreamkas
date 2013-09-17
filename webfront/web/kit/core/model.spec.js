define(function(require) {
    //requirements
    var Model = require('./model');

    var attrs = {
        string: 'test',
        a: {
            b: {
                c: 'test'
            }
        }
    };

    var Model_1 = Model.extend({
        saveData: [
            'string'
        ]
    });

    var Model_2 = Model.extend({
        saveData: function(){
            return {
                a: this.get('a.b.c')
            }
        }
    });

    var model_1 = new Model_1(attrs);
    var model_2 = new Model_2(attrs);

    describe('Lighthouse model', function() {
        it('get method', function() {
            expect(model_1.get('string')).toEqual('test');
            expect(model_1.get('a.b.c')).toEqual('test');
            expect(model_1.get('c.a.b')).toEqual(undefined);
        });

        it('getData method', function() {
            expect(model_1.getData()).toEqual({string: 'test'});
            expect(model_2.getData()).toEqual({a: 'test'});
        });
    });

});