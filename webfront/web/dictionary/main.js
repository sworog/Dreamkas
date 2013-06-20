define(function(require) {
    //requirements
    var _ = require('underscore'),
        dictionary = require('i18n!nls/dictionary');

    LH.text = window.t = function(text) {
        var result = '';

        if (typeof text === 'string'){
            result = _.escape(dictionary[text] || text);
        }

        return result;
    };

    LH.dictionary = _.extend(dictionary,
        require('i18n!nls/userRoles')
    );

    return LH;
});