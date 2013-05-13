define(function() {

    var unitsEnums = {
        kg: {
            capitalFull: "Килограммы",
            smallFull: "килограмм",
            smallShort: "кг"
        },
        unit: {
            capitalFull: "Штуки",
            smallFull: "штука",
            smallShort: "шт."
        },
        liter: {
            capitalFull: "Литры",
            smallFull: "литр",
            smallShort: "л"
        }
    };

    return function(unit, format) {
        return unitsEnums[unit][format];
    }
});