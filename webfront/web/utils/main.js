define(
    [
        './units.js',
        './formatPrice.js'
    ],
    function(units, formatPrice) {
        return {
            units: units,
            formatPrice: formatPrice
        }
    }
);