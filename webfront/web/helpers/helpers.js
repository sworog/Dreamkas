define(function(require) {
    return {
        formatPrice: require('helpers/formatPrice'),
        isEmptyJSON: require('helpers/isEmptyJSON'),
        normalizePrice: require('helpers/normalizePrice'),
        prevalidateInput: require('helpers/prevalidateInput'),
        units: require('helpers/units')
    }
});