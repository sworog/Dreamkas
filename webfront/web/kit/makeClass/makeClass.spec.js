define(function(require, exports, module) {
    //requirements
    var makeClass = require('./makeClass');

    require('lodash');

    describe(module.id, function() {
        var BaseClass;

        beforeEach(function() {
            BaseClass = function() {
            };

            BaseClass.prototype.testValue = null;
        });

        it('extend function does not affect base class', function() {

            var ExtendedClass = makeClass(BaseClass, {
                testValue: 'test extend field'
            });

            var testClass = new BaseClass();

            expect(testClass.testValue).toBeNull();
        });

        it('base class extend', function() {

            var ExtendedClass = makeClass(BaseClass, {
                testValue: 'test extend field'
            });

            var extendedClass = new ExtendedClass();

            expect(extendedClass.testValue).not.toBeNull();
            expect(extendedClass.testValue).toEqual('test extend field');
        });

        it('extend class with constructor', function() {

            var spy = jasmine.createSpy('constructor');

            var ExtendedClass = makeClass(BaseClass, {
                testValue: 'test extend field',
                constructor: spy
            });

            var extendedClass = new ExtendedClass(1, 2);

            expect(spy).toHaveBeenCalledWith(1, 2);
            expect(extendedClass.testValue).toBe('test extend field');
        });

        it('base class deep extend', function() {
            
            var object1 = {
                a: {
                    b: {
                        c: 1,
                        d: 2
                    }
                }
            };

            var object2 = {
                a: {
                    b: {
                        d: 3,
                        e: 4
                    }
                }
            };

            var originalObject1 = _.clone(object1);
            var originalObject2 = _.clone(object2);

            BaseClass.prototype.testValue = object1;

            var ExtendedClass = makeClass(BaseClass, {
                testValue: object2
            });

            var extendedClass = new ExtendedClass();

            expect(extendedClass.testValue.a.b.c).toEqual(1);
            expect(extendedClass.testValue.a.b.d).toEqual(3);
            expect(extendedClass.testValue.a.b.e).toEqual(4);

            expect(object1).toEqual(originalObject1);
            expect(object2).toEqual(originalObject2);
        });

        it('class instant without new', function() {

            var spy = jasmine.createSpy('constructor');

            var ExtendedClass1 = makeClass(BaseClass, {
                testValue: 1
            });

            var ExtendedClass2 = makeClass(ExtendedClass1, {
                testValue: 2,
                constructor: spy
            });

            var ExtendedClass3 = makeClass(ExtendedClass2, {
                testValue: 3
            });

            var extendedClass1 = ExtendedClass1();
            var extendedClass2 = ExtendedClass2(1, 2);
            var extendedClass3 = ExtendedClass3(3, 4);

            expect(spy).toHaveBeenCalledWith(1, 2);
            expect(spy).toHaveBeenCalledWith(3, 4);

            expect(extendedClass1.testValue).toEqual(1);
            expect(extendedClass2.testValue).toEqual(2);
            expect(extendedClass3.testValue).toEqual(3);
        });

        it('base class property is String', function() {

            var ExtendedClass = makeClass(BaseClass, {
                testValue: 'test extend field'
            });
            
            var extendedClass = new ExtendedClass();

            expect(typeof extendedClass.testValue).toEqual('string');
        });

        it('base class property is Boolean', function() {

            var ExtendedClass = makeClass(BaseClass, {
                testValue: false
            });
            
            var extendedClass = new ExtendedClass();

            expect(typeof extendedClass.testValue).toEqual('boolean');
        });

        it('base class property is Number', function() {

            var ExtendedClass = makeClass(BaseClass, {
                testValue: 12
            });
            
            var extendedClass = new ExtendedClass();

            expect(typeof extendedClass.testValue).toEqual('number');
        });

        it('base class property is function', function() {

            var ExtendedClass = makeClass(BaseClass, {
                testValue: function() {
                    return 5;
                }
            });
            
            var extendedClass = new ExtendedClass();

            expect(extendedClass.testValue()).toEqual(5);
        });

        it('base class property is Object', function() {

            var ExtendedClass = makeClass(BaseClass, {
                testValue: {
                    a: 5
                }
            });
            
            var extendedClass = new ExtendedClass();

            expect(extendedClass.testValue.a).toEqual(5);
        });


        it('base class property is Array', function() {

            var ExtendedClass = makeClass(BaseClass, {
                testValue: [5, 6, 7]
            });
            
            var extendedClass = new ExtendedClass();

            expect(extendedClass.testValue).toEqual([5, 6, 7]);
        });

        it('calling super property', function() {

            var ExtendedClass = makeClass(BaseClass, {
                testValue: function(){
                    return BaseClass.prototype.testValue
                }
            });
            
            var extendedClass = new ExtendedClass();

            expect(extendedClass.testValue()).toBeNull();
        });

        it('created class has extend method', function() {
            var ExtendedClass1 = makeClass(BaseClass, {
                testValue: 1
            });

            var ExtendedClass2 = ExtendedClass1.extend({
                testValue: 2
            });

            var extendedClass2 = new ExtendedClass2();

            expect(typeof ExtendedClass1.extend).toBe('function');
            expect(extendedClass2.testValue).toBe(2);
        });

        it('instanceof works right', function(){
            var ExtendedClass1 = makeClass(BaseClass, {
                testValue: 1
            });

            var ExtendedClass2 = makeClass(ExtendedClass1, {
                constructor: function(){
                    this.testValue = 2;
                }
            });

            var extendedClass2 = new ExtendedClass2();

            expect(extendedClass2.testValue).toEqual(2);
            expect(extendedClass2 instanceof ExtendedClass2).toBeTruthy();
            expect(extendedClass2 instanceof ExtendedClass1).toBeTruthy();
        });
    });
});