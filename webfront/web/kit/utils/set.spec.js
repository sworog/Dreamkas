define(function(require) {
    //requirements
    var set = require('./_set');

    require('lodash');

    describe('utils/set', function() {

        var object = {};

        beforeEach(function(){
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

            expect(object.testValue()).toBe(testInstance);
        });
    });
});