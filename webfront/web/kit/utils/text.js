define(function(require) {
    //requirements
    var get = require('./get');

    require('lodash');

    return function(dictionary, text) {
        var result = '';

        if (text && typeof text === 'string'){
            result = get(dictionary, text) || text;
        }

        return result;
    };
});