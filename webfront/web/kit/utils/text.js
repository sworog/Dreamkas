define(function(require) {
    //requirements
    var dictionary = require('dictionary');

    require('lodash');

    return function(text) {
        var result = '';

        if (text && typeof text === 'string'){
            result = dictionary[text] || text;
        }

        return result;
    };
});