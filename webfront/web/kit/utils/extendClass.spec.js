define(function(require) {
    //requirements
    var extendClass = require('./extendClass'),
        nestedObject = require('fixtures/object');

    require('lodash');

    describe('utils/extendClass', function() {
        var BaseClass;

        beforeEach(function() {
            BaseClass = function() {
            };

            BaseClass.prototype.testValue = null;
        });

        it('extend function does not affect base class', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: 'test extend field'
            });

            var testClass = new BaseClass();

            expect(testClass.testValue).toBeNull();
        });

        it('base class extend', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: 'test extend field'
            });

            var extendedClassInstance = new ExtendedClass();

            expect(extendedClassInstance.testValue).not.toBeNull();
            expect(extendedClassInstance.testValue).toEqual('test extend field');
        });

        it('class instant without new', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: 'test extend field'
            });

            var extendedClassInstance = ExtendedClass();

            expect(extendedClassInstance.testValue).not.toBeNull();
            expect(extendedClassInstance.testValue).toEqual('test extend field');
        });

        it('base class property is String', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: 'test extend field'
            });
            
            var extendedClassInstance = new ExtendedClass();

            expect(typeof extendedClassInstance.testValue).toEqual('string');
        });

        it('base class property is Boolean', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: false
            });
            
            var extendedClassInstance = new ExtendedClass();

            expect(typeof extendedClassInstance.testValue).toEqual('boolean');
        });

        it('base class property is Number', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: 12
            });
            
            var extendedClassInstance = new ExtendedClass();

            expect(typeof extendedClassInstance.testValue).toEqual('number');
        });

        it('base class property is function', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: function() {
                    return 5;
                }
            });
            
            var extendedClassInstance = new ExtendedClass();

            expect(extendedClassInstance.testValue()).toEqual(5);
        });

        it('base class property is Object', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: {
                    a: 5
                }
            });
            
            var extendedClassInstance = new ExtendedClass();

            expect(extendedClassInstance.testValue.a).toEqual(5);
        });


        it('base class property is Array', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: [5, 6, 7]
            });
            
            var extendedClassInstance = new ExtendedClass();

            expect(extendedClassInstance.testValue).toEqual([5, 6, 7]);
        });

        it('calling super property', function() {

            var ExtendedClass = extendClass.call(BaseClass, {
                testValue: function(){
                    return BaseClass.prototype.testValue
                }
            });
            
            var extendedClassInstance = new ExtendedClass();

            expect(extendedClassInstance.testValue()).toBeNull();
        });

        it('base class property is NestedObject', function() {

            BaseClass.prototype.nestedObject = nestedObject;

            var ExtendedClass = extendClass.call(BaseClass, {
                nestedObject: {
                    object: {
                        object: {
                            string: 'test string level 5'
                        }
                    }
                }
            });

            var extendedClassInstance = new ExtendedClass();

            expect(extendedClassInstance.nestedObject.object.object.string).toEqual('test string level 5');
            expect(extendedClassInstance.nestedObject.object.object.number).toEqual(3);
            expect(extendedClassInstance.nestedObject.object.string).toEqual('test string level 2');
        });

        it('extendClass without prototype properties', function() {
            var ExtendedClass = extendClass(BaseClass, null, {
                testValue: 'test extend static field'
            });

            var extendedClassInstance = new ExtendedClass();

            expect(extendedClassInstance.testValue).toBeNull();
            expect(ExtendedClass.testValue).toBe('test extend static field');
        });

        it('using extendClass as function', function() {
            var ExtendedClass = extendClass(BaseClass, {
                testValue: 'test extend field'
            });

            var extendedClassInstance = new ExtendedClass();

            expect(extendedClassInstance.testValue).toBe('test extend field');
        });
    });
});