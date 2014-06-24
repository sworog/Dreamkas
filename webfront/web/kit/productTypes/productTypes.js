define(function (require) {
    return function (type, format) {

        var productTypes = {
            unit: {
                units: 'unit',
                nameCapitalFull: 'Штучный',
                nameSmallFull: 'штучный'
            },
            weight: {
                units: 'kg',
                nameCapitalFull: 'Весовой',
                nameSmallFull: 'весовой'
            },
            alcohol: {
                units: 'unit',
                nameCapitalFull: 'Алкоголь',
                nameSmallFull: 'алкоголь'
            }
        };

        return productTypes[type][format];
    }
});