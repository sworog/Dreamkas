define(function(require) {
    //requirements
    require('lodash');

    var getText = function(dictionary, text) {
        var args = [].slice.call(arguments, 0),
            result;

        if (typeof dictionary === 'string') {
            text = dictionary;
            dictionary = {};
            args.splice(0, 1);
        } else {
            args.splice(0, 2);
        }

        dictionary = _.extend({}, getText.dictionary, dictionary);

        if (typeof dictionary[text] === 'function') {
            result = dictionary[text].apply(dictionary, args);
        } else {
            result = getText.compile(dictionary[text], args[0], args[1]);
        }

        return result || text;
    };

    getText.compile = function(input, placeholders, num) {

        var output = input,
            pluralForm = 0;

        if (typeof placeholders === 'number') {
            num = placeholders
        }

        if (typeof num === 'undefined' && _.isArray(placeholders)) {
            num = placeholders[0];
        }

        if (typeof num === 'undefined' && _.isArray(input)) {
            output = input = input[0];
        }

        if (typeof input === 'string') {
            _.forEach(placeholders, function(placeholder, index) {
                var find = '%' + index;
                var re = new RegExp(find, 'g');

                output = output.replace(re, placeholder);
            });
        } else if (_.isArray(input) && input.length === 3) {
            pluralForm = num % 10 == 1 && num % 100 != 11 ? 0 : num % 10 >= 2 && num % 10 <= 4 && (num % 100 < 10 || num % 100 >= 20) ? 1 : 2;
            output = getText.compile(input[pluralForm], placeholders);
        } else if (_.isArray(input) && input.length === 2) {
            pluralForm = (n != 1);
            output = getText.compile(input[pluralForm], placeholders);
        }

        return output;
    };

    return getText;
});