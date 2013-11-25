define(function(require) {
    //requirements
    require('lodash');

    var getText = function(dictionaryList, text) {
        var args = [].slice.call(arguments, 0),
            result;

        if (typeof dictionaryList === 'string'){
            text = dictionaryList;
            dictionaryList = null;
        } else {
            args.splice(0, 2);
        }

        if (!dictionaryList){
            dictionaryList = [];
        } else if (_.isArray(dictionaryList)){
            dictionaryList = dictionaryList.slice(0);
        } else {
            dictionaryList = [dictionaryList];
        }

        dictionaryList = dictionaryList.concat(getText.dictionary || []);

        if (typeof text === 'string'){
            _.find(dictionaryList, function(dictionary){

                if (typeof dictionary[text] === 'function'){
                    result = dictionary[text].apply(dictionary, args);
                } else {
                    result = getText.compile(dictionary[text], args[0], args[1]);
                }

                return result;
            });
        }

        result = result || text;

        return result;
    };

    getText.compile = function(input, placeholders, num){

        var output = input;

        if (typeof placeholders === 'number'){
            num = placeholders
        }

        if (typeof num === 'undefined' && _.isArray(placeholders)){
            num = placeholders[0];
        }

        if (typeof num === 'undefined' && _.isArray(input)){
            output = input = input[0];
        }

        if (typeof input === 'string'){
            _.forEach(placeholders, function(placeholder, index){
                var find = '%' + index;
                var re = new RegExp(find, 'g');

                output = output.replace(re, placeholder);
            });
        } else if (_.isArray(input) && input.length === 3){
            if (num % 10 === 0){
                output = getText.compile(input[2], placeholders);
            } else if (num % 10 === 1){
                output = getText.compile(input[0], placeholders);
            } else if (num % 10 < 5){
                output = getText.compile(input[1], placeholders);
            } else {
                output = getText.compile(input[2], placeholders);
            }
        } else if (_.isArray(input) && input.length === 2){
            if (num > 1){
                output = getText.compile(input[1], placeholders);
            } else {
                output = getText.compile(input[0], placeholders);
            }
        }

        return output;
    };

    return getText;
});