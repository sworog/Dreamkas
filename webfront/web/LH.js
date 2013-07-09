define(function(require) {
    //requirements
    var _ = require('underscore');

    return window.LH = _.extend({
        attr: require('utils/attr'),
        isAllow: require('utils/isAllow'),
        formatPrice: require('utils/formatPrice'),
        isEmptyJSON: require('utils/isEmptyJSON'),
        normalizePrice: require('utils/normalizePrice'),
        prevalidateInput: require('utils/prevalidateInput'),
        units: require('utils/units')
    }, window.LH);
});