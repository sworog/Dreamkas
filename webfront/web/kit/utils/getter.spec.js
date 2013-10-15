define(function(require) {
    //requirements
    var getter = require('./getter');
    
    require('lodash');
    
    describe('utils/getter', function(){
        
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
                }
            };

            object.objectValue = _.clone(object);
            
            _.extend(object, getter);
            
        });

        it('get null value', function(){
            expect(object.get('nullValue')).toBeNull();
            expect(object.get('objectValue.nullValue')).toBeNull();
        });

        it('get true value', function(){
            expect(object.get('trueValue')).toBeTruthy();
            expect(object.get('objectValue.trueValue')).toBeTruthy();
        });

        it('get false value', function(){
            expect(object.get('falseValue')).toBeFalsy();
            expect(object.get('objectValue.falseValue')).toBeFalsy();
        });

        it('get string value', function(){
            expect(object.get('stringValue')).toBe('string value');
            expect(object.get('objectValue.stringValue')).toBe('string value');
        });

        it('get numberValue value', function(){
            expect(object.get('numberValue')).toBe(1);
            expect(object.get('objectValue.numberValue')).toBe(1);
        });

        it('get zeroValue value', function(){
            expect(object.get('zeroValue')).toBe(0);
            expect(object.get('objectValue.zeroValue')).toBe(0);
        });

        it('get undefinedValue value', function(){
            expect(object.get('undefinedValue')).toBeUndefined();
            expect(object.get('objectValue.undefinedValue')).toBeUndefined();
        });

        it('get functionValue value', function(){
            expect(object.get('functionValue')).toEqual(object);
            expect(object.get('objectValue.functionValue')).toEqual(object);
            expect(object.get('functionValue.stringValue')).toBe('string value');
        });

        it('get nonexistent value', function(){
            expect(object.get('nonexistentValue')).toBeUndefined();
            expect(object.get('objectValue.nonexistentValue')).toBeUndefined();
            expect(object.get('objectValue.nonexistentValue.stringValue')).toBeUndefined();
        });
    });
});