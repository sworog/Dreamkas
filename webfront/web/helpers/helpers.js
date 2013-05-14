define(function(require) {
    return {
        formatPrice: require('./formatPrice.js'),
        isEmptyJSON: require('./isEmptyJSON.js'),
        normalizePrice: require('./normalizePrice.js'),
        prevalidateInput: require('./prevalidateInput.js'),
        units: require('./units.js')
    }
});