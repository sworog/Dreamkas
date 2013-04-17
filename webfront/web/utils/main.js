define(
    [
        './units.js',
        './formatPrice.js',
        './isEmptyJSON.js'
    ],
    function(units, formatPrice, isEmptyJSON) {
        return {
            units: units,
            formatPrice: formatPrice,
            isEmptyJSON: isEmptyJSON
        }
    }
);