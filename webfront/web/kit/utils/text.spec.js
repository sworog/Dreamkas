define(function(require) {
    //requirements
    var text = require('./text');

    require('lodash');

    describe('utils/text', function(){
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

            text(dictionary, 'string');
            text(dictionary, 'object.string');

            expect(originalDictionary).toEqual(dictionary);
        });

        it('get string from dictionary', function(){
            expect(text(dictionary, 'string')).toBe('test string');
        });

        it('get string from dictionary nested object', function(){
            expect(text(dictionary, 'object.string')).toBe('test object string');
        });

        it('get nonexistent string from dictionary', function(){
            expect(text(dictionary, 'nonexistent string')).toBe('nonexistent string');
        });

        it('get string from undefined dictionary', function(){
            expect(text(dict, 'nonexistent string')).toBe('nonexistent string');
        });
    });
});