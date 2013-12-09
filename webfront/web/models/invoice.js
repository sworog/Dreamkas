define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        compute = require('kit/utils/computeAttr'),
        currentUserModel = require('models/currentUser');

    return Model.extend({
        modelName: 'invoice',
        urlRoot: function() {
            if(currentUserModel.stores.length) {
                return LH.baseApiUrl + '/stores/' + currentUserModel.stores.at(0).id + '/invoices'
            }
        },

        defaults: {
            includesVAT: true,
            totalAmountVATFormatted: compute(['totalAmountVAT'], function(totalAmountVAT){
                return LH.formatMoney(totalAmountVAT)
            })
        },

        dateFormat: 'dd.mm.yy',
        datePrintFormat: "dd.mm.yyyy",
        timeFormat: 'HH:mm',
        invalidMessage: 'Вы ввели неверную дату',

        saveData: [
            'sku',
            'supplier',
            'acceptanceDate',
            'accepter',
            'legalEntity',
            'includesVAT',
            'supplierInvoiceSku',
            'supplierInvoiceDate'
        ]
    });
});