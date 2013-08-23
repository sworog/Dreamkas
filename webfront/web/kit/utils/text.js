define(function(require) {
    //requirements
    var dictionary = require('dictionary'),
        _ = require('underscore');

    return function(text) {
        var result = '';

        if (text && typeof text === 'string'){
            result = dictionary[text] || text;
        }

        return result;
    };
});