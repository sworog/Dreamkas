define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        defaults: {
            organizationId: null
        },
        urlRoot: function(){
            return Model.baseApiUrl + '/organizations/' + this.get('organizationId') + '/bankAccounts'
        },
        saveData: [
            'bic',
            'bankName',
            'bankAddress',
            'correspondentAccount',
            'account'
        ]
    });
});