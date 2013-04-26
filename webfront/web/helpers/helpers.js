define(
    [
        './units.js',
        './formatPrice.js',
        './prevalidateInput.js',
        './normalizePrice.js',
        './isEmptyJSON.js'
    ],
    function(units, formatPrice, prevalidateInput, normalizePrice, isEmptyJSON) {
        return {
            units: units,
            formatPrice: formatPrice,
            prevalidateInput: prevalidateInput,
            normalizePrice: normalizePrice,
            isEmptyJSON: isEmptyJSON
        }
    }
);