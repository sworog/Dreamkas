define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        modelName: 'writeOff',
        urlRoot: LH.baseApiUrl + '/writeoffs',

        dateFormat: 'dd.mm.yy',
        datePrintFormat: "dd.mm.yyyy",
        timeFormat: 'HH:mm',
        invalidMessage: 'Вы ввели неверную дату',

        saveData: [
            'number',
            'date'
        ]
    });
});