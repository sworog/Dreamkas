define(function(require) {
    //requirements
    var _ = require('underscore'),
        dictionary = require('i18n!nls/dictionary');

    LH.text = window.t = function(text) {
        return text ? _.escape(dictionary[text] || dictionary[text.toLowerCase()] || text) : '';
    };

    LH.dictionary = _.extend(dictionary,
        require('i18n!nls/userRoles')
    );

    return LH;
});