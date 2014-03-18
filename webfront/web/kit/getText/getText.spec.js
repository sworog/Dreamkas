define(function(require, exports, module) {
    //requirements
    var getText = require('./getText');

    require('lodash');

    describe(module.id, function(){
        var dictionary = {},
            dict;

        beforeEach(function(){

            delete getText.dictionary;

            dictionary = {
                string: 'test string',
                func: function(num){
                    return 'string plus ' + num;
                },
                'Text with dot. And some more text': 'Текст с точкой. И еще немного текста',
                'text with %0 and %1': 'текст содержит %0 и %1',
                'text with %placeholder_1 and %placeholder_2': 'текст содержит %placeholder_1 и %placeholder_2',
                '%0 test': ['%0 test', '%0 tests'],
                'test': ['test', 'tests'],
                '%0 чертей': ['%0 черт', '%0 чертика', '%0 чертей'],
                'черт': ['черт', 'чертика', 'чертей']
            }
        });

        it('text function does not affect dictionary object', function(){

            var originalDictionary = _.clone(dictionary);
            var originalStaticDictionary = {a: 1, b: 2};

            getText.dictionary = originalStaticDictionary;

            getText(dictionary, 'string');
            getText(dictionary, 'object.string');

            expect(originalDictionary).toEqual(dictionary);
            expect(originalStaticDictionary).toEqual(getText.dictionary);
        });

        it('get string from dictionary', function(){
            expect(getText(dictionary, 'string')).toBe('test string');
        });

        it('get nonexistent string from dictionary', function(){
            expect(getText(dictionary, 'nonexistent string')).toBe('nonexistent string');
        });

        it('get string from undefined dictionary', function(){
            expect(getText(dict, 'nonexistent string')).toBe('nonexistent string');
        });

        it('get number from dictionary', function(){
            expect(getText(dict, 2)).toBe(2);
        });

        it('get function from dictionary', function(){
            expect(getText(dictionary, 'func', 2)).toBe('string plus 2');
        });

        it('get string from static dictionary', function(){

            getText.dictionary = dictionary;

            expect(getText('string')).toBe('test string');
        });

        it('get text with dot', function(){
            expect(getText(dictionary, 'Text with dot. And some more text')).toBe('Текст с точкой. И еще немного текста');
        });

        it('get text with numeric placeholders', function(){
            expect(getText(dictionary, 'text with %0 and %1', [1, 2])).toBe('текст содержит 1 и 2');
        });

        it('get text with named placeholders', function(){
            expect(getText(dictionary, 'text with %placeholder_1 and %placeholder_2', {
                placeholder_1: 3,
                placeholder_2: 4
            })).toBe('текст содержит 3 и 4');
        });

        it('get text with num = 1 in en', function(){
            expect(getText(dictionary, '%0 test', [1])).toBe('1 test');
        });

        it('get text with num = 2 in en', function(){
            expect(getText(dictionary, '%0 test', [2])).toBe('2 tests');
        });

        it('get text with num = 0 in ru', function(){
            expect(getText(dictionary, '%0 чертей', [0])).toBe('0 чертей');
        });

        it('get text with num = 1 in ru', function(){
            expect(getText(dictionary, '%0 чертей', [1])).toBe('1 черт');
        });

        it('get text with num = 8 in ru', function(){
            expect(getText(dictionary, '%0 чертей', [8])).toBe('8 чертей');
        });

        it('get text with num = 1000 in ru', function(){
            expect(getText(dictionary, '%0 чертей', [1000])).toBe('1000 чертей');
        });

        it('get one in en', function(){
            expect(getText(dictionary, 'test', 1)).toBe('test');
        });

        it('get many in en', function(){
            expect(getText(dictionary, 'test', 10)).toBe('tests');
        });

        it('get zero in ru', function(){
            expect(getText(dictionary, 'черт', 0)).toBe('чертей');
        });

        it('get one in ru', function(){
            expect(getText(dictionary, 'черт', 1)).toBe('черт');
        });

        it('get few in ru', function(){
            expect(getText(dictionary, 'черт', 4)).toBe('чертика');
        });

        it('get many in ru', function(){
            expect(getText(dictionary, 'черт', 8)).toBe('чертей');
        });

        it('get 1000 in ru', function(){
            expect(getText(dictionary, 'черт', 1000)).toBe('чертей');
        });

        it('get text from array', function(){
            expect(getText(dictionary, 'test')).toBe('test');
        });
    });
});