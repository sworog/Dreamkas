define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        currentUserModel = require('models/currentUser');

    return Model.extend({
        modelName: 'writeOff',
        urlRoot: function() {
            if(currentUserModel.stores.length) {
                return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/writeoffs'
            }
        },

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