define(function(require) {
    //requirements
    //requirements
    var _ = require('underscore'),
        dictionary = require('nls');

    return KIT.text = function(text) {
        var result = '';

        if (text && typeof text === 'string'){
            result = _.escape(dictionary[text] || text);
        }

        return result;
    };

});