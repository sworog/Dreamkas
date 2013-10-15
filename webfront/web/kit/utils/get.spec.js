define(function(require) {
    //requirements
    var get = require('./get');

    require('lodash');

    describe('utils/get', function(){

        var object = {},
            obj;

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
                value: 'value',
                'get:value': function(){
                    return 'get value throw getter function'
                }
            };

            object.objectValue = _.clone(object);
        });

        it('get does not affect object properties', function() {

            var originalObject = _.cloneDeep(object);

            get(object, 'nullValue');
            get(object, 'trueValue');
            get(object, 'falseValue');
            get(object, 'stringValue');
            get(object, 'numberValue');
            get(object, 'zeroValue');
            get(object, 'undefinedValue');
            get(object, 'functionValue');
            get(object, 'objectValue');

            expect(object).toEqual(originalObject);
        });

        it('get null value', function(){
            expect(get(object, 'nullValue')).toBeNull();
            expect(get(object, 'objectValue.nullValue')).toBeNull();
        });

        it('get true value', function(){
            expect(get(object, 'trueValue')).toBeTruthy();
            expect(get(object, 'objectValue.trueValue')).toBeTruthy();
        });

        it('get false value', function(){
            expect(get(object, 'falseValue')).toBeFalsy();
            expect(get(object, 'objectValue.falseValue')).toBeFalsy();
        });

        it('get string value', function(){
            expect(get(object, 'stringValue')).toBe('string value');
            expect(get(object, 'objectValue.stringValue')).toBe('string value');
        });

        it('get numberValue value', function(){
            expect(get(object, 'numberValue')).toBe(1);
            expect(get(object, 'objectValue.numberValue')).toBe(1);
        });

        it('get zeroValue value', function(){
            expect(get(object, 'zeroValue')).toBe(0);
            expect(get(object, 'objectValue.zeroValue')).toBe(0);
        });

        it('get undefinedValue value', function(){
            expect(get(object, 'undefinedValue')).toBeUndefined();
            expect(get(object, 'objectValue.undefinedValue')).toBeUndefined();
        });

        it('get functionValue value', function(){
            expect(get(object, 'functionValue')).toEqual(object);
            expect(get(object, 'objectValue.functionValue')).toEqual(object);
            expect(get(object, 'functionValue.stringValue')).toBe('string value');
        });

        it('get nonexistent value', function(){
            expect(get(object, 'nonexistentValue')).toBeUndefined();
            expect(get(object, 'objectValue.nonexistentValue')).toBeUndefined();
            expect(get(object, 'objectValue.nonexistentValue.stringValue')).toBeUndefined();
        });

        it('get value from undefined object', function(){
            expect(get(obj, 'nonexistentValue')).toBeUndefined();
        });

        it('get value throw getter function', function(){
            expect(get(object, 'value')).toBe('get value throw getter function');
        });

    });
});