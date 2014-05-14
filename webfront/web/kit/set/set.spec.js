define(function(require, exports, module) {
    //requirements
    var set = require('./set'),
        events = require('../events/events');

    require('lodash');

    describe(module.id, function() {

        var object = {};

        beforeEach(function() {
            object = {};
        });

        it('set null value by hash', function() {
            set(object, {
                testValue: null,
                a: {
                    b: null
                }
            });

            expect(object.testValue).toEqual(null);
            expect(object.a.b).toEqual(null);
        });

        it('set null value by path', function() {
            set(object, 'testValue', null);
            set(object, 'a.b', null);

            expect(object.testValue).toEqual(null);
            expect(object.a.b).toEqual(null);
        });

        it('set bool value by hash', function() {
            set(object, {
                testValue: false,
                a: {
                    b: false
                }
            });

            expect(object.testValue).toBeFalsy();
            expect(object.a.b).toBeFalsy();
        });

        it('set bool value by path', function() {
            set(object, 'testValue', false);
            set(object, 'a.b', false);

            expect(object.testValue).toBeFalsy();
            expect(object.a.b).toBeFalsy();
        });

        it('set string value by hash', function() {
            set(object, {
                testValue: 'test string',
                a: {
                    b: 'test string'
                }
            });

            expect(object.testValue).toEqual('test string');
            expect(object.a.b).toEqual('test string');
        });

        it('set string value by path', function() {
            set(object, 'testValue', 'test string');
            set(object, 'a.b', 'test string');

            expect(object.testValue).toEqual('test string');
            expect(object.a.b).toEqual('test string');
        });

        it('set number value by hash', function() {
            set(object, {
                testValue: 1,
                a: {
                    b: 1
                }
            });

            expect(object.testValue).toEqual(1);
            expect(object.a.b).toEqual(1);
        });

        it('set number value by path', function() {
            set(object, 'testValue', 1);
            set(object, 'a.b', 1);

            expect(object.testValue).toEqual(1);
            expect(object.a.b).toEqual(1);
        });

        it('set zero value by hash', function() {
            set(object, {
                testValue: 0,
                a: {
                    b: 0
                }
            });

            expect(object.testValue).toEqual(0);
            expect(object.a.b).toEqual(0);
        });

        it('set zero value by path', function() {
            set(object, 'testValue', 0);
            set(object, 'a.b', 0);

            expect(object.testValue).toEqual(0);
            expect(object.a.b).toEqual(0);
        });

        it('set function value by hash', function() {
            set(object, {
                testValue: function(){
                    return 1;
                },
                a: {
                    b: function(){
                        return 1;
                    }
                }
            });

            expect(object.testValue()).toEqual(1);
            expect(object.a.b()).toEqual(1);
        });

        it('set function value by path', function() {
            set(object, 'testValue', function(){
                return 1;
            });

            set(object, 'a.b', function(){
                return 1;
            });

            expect(object.testValue()).toEqual(1);
            expect(object.a.b()).toEqual(1);
        });

        it('set instance value by hash', function() {

            var TestClass = function(param){
                this.param = param;
            };

            var testInstance = new TestClass('test');

            set(object, {
                testValue: testInstance,
                a: {
                    b: testInstance
                }
            });

            expect(object.testValue).toEqual(testInstance);
            expect(object.a.b).toEqual(testInstance);
        });

        it('set instance value by path', function() {

            var TestClass = function(param){
                this.param = param;
            };

            var testInstance = new TestClass('test');

            set(object, 'testValue', testInstance);
            set(object, 'a.b', testInstance);

            expect(object.testValue).toEqual(testInstance);
            expect(object.a.b).toEqual(testInstance);
        });

        it('set plain object value by hash', function() {

            var plainObject = {
                a: 1,
                b: 2
            };

            set(object, {
                testValue: plainObject,
                a: {
                    b: plainObject
                }
            });

            expect(object.testValue).toEqual(plainObject);
            expect(object.a.b).toEqual(plainObject);
        });

        it('set plain object value by path', function() {

            var plainObject = {
                a: 1,
                b: 2
            };

            set(object, 'testValue', plainObject);
            set(object, 'a.b', plainObject);

            expect(object.testValue).toEqual(plainObject);
            expect(object.a.b).toEqual(plainObject);
        });

        it('set:callback by path', function() {

            var spyTestValue = jasmine.createSpy('testValue'),
                spyAll = jasmine.createSpy('all');


            object = {
                'set:testValue': spyTestValue,
                'set:*': spyAll
            };

            set(object, 'testValue', 1);

            expect(spyTestValue).toHaveBeenCalledWith(1, {});
            expect(spyAll).toHaveBeenCalledWith({ testValue : 1 }, {});
        });

        it('set:callback by hash', function() {

            var spyTestValue = jasmine.createSpy('testValue'),
                spyAll = jasmine.createSpy('all');


            object = {
                'set:testValue': spyTestValue,
                'set:testValue1': spyTestValue,
                'set:*': spyAll
            };

            set(object, {
                testValue: 1,
                testValue1: 2
            });

            expect(spyTestValue).toHaveBeenCalledWith(1, {});
            expect(spyTestValue).toHaveBeenCalledWith(2, {});
            expect(spyAll).toHaveBeenCalledWith({
                testValue: 1,
                testValue1: 2
            }, {});
        });

        it('change event by path', function() {

            var spy = jasmine.createSpy();

            _.extend(object, events);

            object.on('change:testValue', spy);

            set(object, 'testValue', 1);

            expect(spy).toHaveBeenCalledWith(1, {});
        });

        it('change event by hash', function() {

            var spy = jasmine.createSpy();

            _.extend(object, events);

            object.on('change:testValue', spy);

            set(object, {
                testValue: 1
            });

            expect(spy).toHaveBeenCalledWith(1, {});
        });

        it ('does not trigger same data set', function(){

            var spy = jasmine.createSpy();

            _.extend(object, {
                testValue: 1
            }, events);

            object.on('change:testValue', spy);

            set(object, 'testValue', 1);

            expect(spy).not.toHaveBeenCalled();
        });

        it ('set data in setter callback', function(){

            var spy = jasmine.createSpy();

            _.extend(object, {
                testValue: 1,
                'set:testValue': function(){
                    this.testValue = 1;
                }
            }, events);

            object.on('change:testValue', spy);

            set(object, 'testValue', 2);

            expect(object.testValue).toEqual(1);
            expect(spy).not.toHaveBeenCalled();
        });
    });
});