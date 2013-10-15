define(function(require) {
    //requirements
    var translate = require('./translate');

    require('lodash');

    describe('utils/translate', function(){
        var dictionary = {},
            dict;

        beforeEach(function(){
            dictionary = {
                string: 'test string',
                object: {
                    string: 'test object string'
                }
            }
        });

        it('text function does not affect dictionary object', function(){

            var originalDictionary = _.clone(dictionary);

            translate(dictionary, 'string');
            translate(dictionary, 'object.string');

            expect(originalDictionary).toEqual(dictionary);
        });

        it('get string from dictionary', function(){
            expect(translate(dictionary, 'string')).toBe('test string');
        });

        it('get string from dictionary nested object', function(){
            expect(translate(dictionary, 'object.string')).toBe('test object string');
        });

        it('get nonexistent string from dictionary', function(){
            expect(translate(dictionary, 'nonexistent string')).toBe('nonexistent string');
        });

        it('get string from undefined dictionary', function(){
            expect(translate(dict, 'nonexistent string')).toBe('nonexistent string');
        });

        it('get number from dictionary', function(){
            expect(translate(dict, 2)).toBe(2);
        });
    });
});