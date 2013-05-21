define(
    [
        './baseModel.js'
    ],
    function(baseModel) {
    return baseModel.extend({
        modelName: 'writeOff',
        urlRoot: baseApiUrl + '/writeoffs',

        dateFormat: 'dd.mm.yy',
        datePrintFormat: "dd.mm.yyyy",
        timeFormat: 'HH:mm',
        invalidMessage: 'Вы ввели неверную дату',

        saveFields: [
            'number',
            'date'
        ]
    });
});