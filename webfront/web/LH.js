define(function(require) {
    window.LH = _.extend({
        isAllow: require('utils/isAllow'),
        text: require('kit/utils/text'),
        formatPrice: require('utils/formatPrice'),
        isEmptyJSON: require('utils/isEmptyJSON'),
        normalizePrice: require('utils/normalizePrice'),
        prevalidateInput: require('utils/prevalidateInput'),
        units: require('utils/units')
    }, window.LH);
});