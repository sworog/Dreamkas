define(function(require) {
    //requirements
    var _ = require('underscore'),
        dictionary = require('i18n!nls/dictionary');

    LH.text = window.t = function(text) {
        return dictionary[text] || dictionary[text.toLowerCase()] || text;
    };

    LH.dictionary = _.extend(dictionary, {
        userRoles: require('i18n!nls/userRoles')
    });

    return LH;
});