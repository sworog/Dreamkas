define(function(require) {
    //requirements
    var set = require('./set');

    require('lodash');
    require('backbone');

    describe('utils/set', function() {

        var object = {};

        beforeEach(function() {
            object = {};
        });

        it('set null value by hash', function() {
            set(object, {
                testValue: null
            });

            expect(object.testValue).toBe(null);
        });

        it('set null value by path', function() {
            set(object, 'testValue', null);

            expect(object.testValue).toBe(null);
        });

        it('set false value by hash', function() {
            set(object, {
                testValue: false
            });

            expect(object.testValue).toBeFalsy();
        });

        it('set false value by path', function() {
            set(object, 'testValue', false);

            expect(object.testValue).toBeFalsy();
        });

        it('set true value by hash', function() {
            set(object, {
                testValue: true
            });

            expect(object.testValue).toBeTruthy();
        });

        it('set true value by path', function() {
            set(object, 'testValue', true);

            expect(object.testValue).toBeTruthy();
        });

        it('set string value by hash', function() {
            set(object, {
                testValue: 'test string'
            });

            expect(object.testValue).toBe('test string');
        });

        it('set string value by path', function() {
            set(object, 'testValue', 'test string');

            expect(object.testValue).toBe('test string');
        });

        it('set number value by hash', function() {
            set(object, {
                testValue: 1
            });

            expect(object.testValue).toBe(1);
        });

        it('set number value by path', function() {
            set(object, 'testValue', 1);

            expect(object.testValue).toBe(1);
        });

        it('set zero value by hash', function() {
            set(object, {
                testValue: 0
            });

            expect(object.testValue).toBe(0);
        });

        it('set zero value by path', function() {
            set(object, 'testValue', 0);

            expect(object.testValue).toBe(0);
        });

        it('set function value by hash', function() {
            set(object, {
                testValue: function(){
                    return 1;
                }
            });

            expect(object.testValue()).toBe(1);
        });

        it('set function value by path', function() {
            set(object, 'testValue', function(){
                return 1;
            });

            expect(object.testValue()).toBe(1);
        });

        it('set instance value by hash', function() {

            var TestClass = function(param){
                this.param = param;
            };

            var testInstance = new TestClass('test');

            set(object, {
                testValue: testInstance
            });

            expect(object.testValue).toBe(testInstance);
        });

        it('set instance value by path', function() {

            var TestClass = function(param){
                this.param = param;
            };

            var testInstance = new TestClass('test');

            set(object, 'testValue', testInstance);

            expect(object.testValue).toBe(testInstance);
        });

        it('set plain object value by hash', function() {

            var plainObject = {
                a: 1,
                b: 2
            };

            set(object, {
                testValue: plainObject
            });

            expect(object.testValue).toEqual(plainObject);
        });

        it('set plain object value by path', function() {

            var plainObject = {
                a: 1,
                b: 2
            };

            set(object, 'testValue', plainObject);

            expect(object.testValue).toEqual(plainObject);
        });

        it('set:callback', function() {

            var spy = jasmine.createSpy();

            object = {
                'set:testValue': spy
            };

            set(object, 'testValue', 1);

            expect(spy).toHaveBeenCalledWith(1, undefined);
        });

        it('set:callback modify data', function() {

            var spy = jasmine.createSpy().andReturn('test string');

            object = {
                'set:testValue': function(testValue){
                    return 'string' + testValue;
                }
            };

            set(object, 'testValue', 1);

            expect(object.testValue).toBe('string1');
        });

        it('set event', function() {

            var spy = jasmine.createSpy();

            _.extend(object, Backbone.Events);

            object.on('set:testValue', spy);

            set(object, 'testValue', 1);

            expect(spy).toHaveBeenCalledWith(1, undefined);
        });

        it('set complex object', function() {

            var spy = jasmine.createSpy();

            var plainObject = {
                a: 1,
                b: 2
            };

            _.extend(object, {
                'set:testValue': function(testValue){
                    testValue.a = testValue.a * 2;
                    testValue.b = testValue.b * 2;
                },
                'set:testValue.a': function(a){
                    return a + 1;
                },
                'set:testValue.b': function(b){
                    return b + 1;
                }
            }, Backbone.Events);

            object.on('set:testValue.a', spy);
            object.on('set:testValue.b', spy);
            object.on('set:testValue', spy);

            set(object, 'testValue', plainObject);

            expect(object.testValue.a).toBe(3);
            expect(object.testValue.b).toBe(5);

            expect(spy).toHaveBeenCalledWith(3, undefined);
            expect(spy).toHaveBeenCalledWith(5, undefined);
            expect(spy).toHaveBeenCalledWith(plainObject, undefined);

        });

        it ('does not set same data', function(){

            var spy = jasmine.createSpy();

            _.extend(object, {
                testValue: 1,
                'set:testValue': spy
            }, Backbone.Events);

            object.on('set:testValue', spy);

            set(object, 'testValue', 1);

            expect(spy).not.toHaveBeenCalled();
        });
    });
});