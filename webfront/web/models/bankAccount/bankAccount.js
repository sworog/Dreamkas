define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        defaults: {
            organizationId: null,
            supplierId: null
        },
        urlRoot: function(){

            var url;

            if (this.get('organizationId')){
                url = Model.baseApiUrl + '/organizations/' + this.get('organizationId') + '/bankAccounts';
            }

            if (this.get('supplierId')){
                url = Model.baseApiUrl + '/organizations/' + this.get('supplierId') + '/bankAccounts';
            }

            return url;
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