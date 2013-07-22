define(function(require) {
    //requirements

    return _.extend(
        require('i18n!nls/common'),
        require('i18n!nls/userRoles'),
        require('i18n!nls/formErrors')
    );
});