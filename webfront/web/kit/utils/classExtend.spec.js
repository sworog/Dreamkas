define(function(require) {
    //requirements
    var classExtend = require('./classExtend'),
        nestedObject = require('../fixtures/object');

    require('lodash');

    describe('utils/classExtend', function() {
        var Klass;

        beforeEach(function() {
            Klass = function() {
            };

            Klass.prototype.testValue = null;

            Klass.extend = classExtend;

        });

        it('base class property is not changed after extending', function() {

            var ExtendedKlass = Klass.extend();
            var testKlass = new ExtendedKlass();

            expect(testKlass.testValue).toBeNull();
        });

        it('base class property is not changed after calling extend function', function() {

            var ExtendedKlass = Klass.extend({
                testValue: 'test extend field'
            });

            var testKlass = new Klass();

            expect(testKlass.testValue).toBeNull();
        });

        it('base class extend', function() {

            var ExtendedKlass = Klass.extend({
                testValue: 'test extend field'
            });
            var testKlass = new ExtendedKlass();

            expect(testKlass.testValue).not.toBeNull();
            expect(testKlass.testValue).toEqual('test extend field');
        });

        it('base class property is String', function() {

            var ExtendedKlass = Klass.extend({
                testValue: 'test extend field'
            });
            var testKlass = new ExtendedKlass();

            expect(typeof testKlass.testValue).toEqual('string');

        });

        it('base class property is Boolean', function() {

            var ExtendedKlass = Klass.extend({
                testValue: false
            });
            var testKlass = new ExtendedKlass();

            expect(typeof testKlass.testValue).toEqual('boolean');

        });

        it('base class property is Number', function() {

            var ExtendedKlass = Klass.extend({
                testValue: 12
            });
            var testKlass = new ExtendedKlass();

            expect(typeof testKlass.testValue).toEqual('number');

        });

        it('base class property is function', function() {

            var ExtendedKlass = Klass.extend({
                testValue: function() {
                    return 5;
                }
            });
            var testKlass = new ExtendedKlass();

            expect(testKlass.testValue()).toEqual(5);

        });

        it('base class property is Object', function() {

            var ExtendedKlass = Klass.extend({
                testValue: {
                    a: 5
                }
            });
            var testKlass = new ExtendedKlass();

            expect(testKlass.testValue.a).toEqual(5);

        });


        it('base class property is Array', function() {

            var ExtendedKlass = Klass.extend({
                testValue: [5, 6, 7]
            });
            var testKlass = new ExtendedKlass();

            expect(testKlass.testValue).toEqual([5, 6, 7]);

        });

        it('base class property is NestedObject', function() {

            Klass.prototype.nestedObject = nestedObject;

            var ExtendedKlass = Klass.extend({
                nestedObject: {
                    object: {
                        object: {
                            string: 'test string level 5'
                        }
                    }
                }
            });

            var testKlass = new ExtendedKlass();

            expect(testKlass.nestedObject.object.object.string).toEqual('test string level 5');
            expect(testKlass.nestedObject.object.object.number).toEqual(3);
            expect(testKlass.nestedObject.object.string).toEqual('test string level 2');

        });

    });

});