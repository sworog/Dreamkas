define(function(require) {
    //requirements
    //requirements
    var _ = require('underscore'),
        dictionary = require('nls');

    return window.t = function(text) {
        var result = '';

        if (typeof text === 'string'){
            result = _.escape(dictionary[text] || text);
        }

        return result;
    };

});