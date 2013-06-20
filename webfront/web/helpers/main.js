define(function(require) {
    return LH.helpers = {
        text: require('helpers/text'),
        formatPrice: require('helpers/formatPrice'),
        isEmptyJSON: require('helpers/isEmptyJSON'),
        normalizePrice: require('helpers/normalizePrice'),
        prevalidateInput: require('helpers/prevalidateInput'),
        units: require('helpers/units')
    }
});