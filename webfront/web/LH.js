define(function(require) {
    window.LH = window.Lighthouse = _.extend({
        isAllow: require('kit/utils/isAllow'),
        text: require('kit/utils/text'),
        formatPrice: require('utils/formatPrice'),
        isEmptyJSON: require('utils/isEmptyJSON'),
        normalizePrice: require('utils/normalizePrice'),
        prevalidateInput: require('utils/prevalidateInput'),
        units: require('utils/units')
    }, window.LH, window.Lighthouse);
});