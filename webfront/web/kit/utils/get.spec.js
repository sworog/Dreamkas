define(function(require) {
    //requirements
    var get = require('./get');

    require('lodash');

    describe('utils/get', function(){

        var object = {};

        beforeEach(function(){
            object = {
                nullValue: null,
                trueValue: true,
                falseValue: false,
                stringValue: 'string value',
                numberValue: 1,
                zeroValue: 0,
                undefinedValue: undefined,
                functionValue: function(){
                    return _.clone(object);
                },
                objectValue: _.clone(object)
            };
        });

        it('get does not affect object properties', function() {

            var originalObject = _.cloneDeep(object);

            get.call(object, 'nullValue');
            get.call(object, 'trueValue');
            get.call(object, 'falseValue');
            get.call(object, 'stringValue');
            get.call(object, 'numberValue');
            get.call(object, 'zeroValue');
            get.call(object, 'undefinedValue');
            get.call(object, 'functionValue');
            get.call(object, 'objectValue');

            expect(object).toEqual(originalObject);
        });

        it('get null value', function(){
            expect(get.call(object, 'nullValue')).toBeNull();
            expect(get.call(object, 'objectValue.nullValue')).toBeNull();
        });

        it('get true value', function(){
            expect(get.call(object, 'trueValue')).toBeTruthy();
            expect(get.call(object, 'objectValue.trueValue')).toBeTruthy();
        });

        it('get false value', function(){
            expect(get.call(object, 'falseValue')).toBeFalsy();
            expect(get.call(object, 'objectValue.falseValue')).toBeFalsy();
        });

        it('get string value', function(){
            expect(get.call(object, 'stringValue')).toBe('string value');
            expect(get.call(object, 'objectValue.stringValue')).toBe('string value');
        });

        it('get numberValue value', function(){
            expect(get.call(object, 'numberValue')).toBe(1);
            expect(get.call(object, 'objectValue.numberValue')).toBe(1);
        });

        it('get zeroValue value', function(){
            expect(get.call(object, 'zeroValue')).toBe(0);
            expect(get.call(object, 'objectValue.zeroValue')).toBe(0);
        });

        it('get undefinedValue value', function(){
            expect(get.call(object, 'undefinedValue')).toBeUndefined();
            expect(get.call(object, 'objectValue.undefinedValue')).toBeUndefined();
        });

        it('get functionValue value', function(){
            expect(get.call(object, 'functionValue')).toEqual(object);
            expect(get.call(object, 'objectValue.functionValue')).toEqual(object);
            expect(get.call(object, 'functionValue.stringValue')).toBe('string value');
        });

        it('get nonexistent value', function(){
            expect(get.call(object, 'nonexistentValue')).toBeUndefined();
            expect(get.call(object, 'objectValue.nonexistentValue')).toBeUndefined();
            expect(get.call(object, 'objectValue.nonexistentValue.stringValue')).toBeUndefined();
        });

    });
});