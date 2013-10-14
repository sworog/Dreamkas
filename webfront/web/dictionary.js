define(function(require) {
    //requirements
    require('lodash');

    return _.extend(
        require('i18n!nls/common'),
        require('i18n!nls/userRoles'),
        require('i18n!nls/formErrors'),
        require('i18n!nls/log'),
        require('i18n!nls/statuses'),
        require('i18n!nls/priceRoundings')
    );
});