define(function (require) {
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

    return function (type, format) {
        return productTypes[type][format];
    }
});