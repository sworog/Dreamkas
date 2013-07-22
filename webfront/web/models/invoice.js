define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        modelName: 'invoice',
        urlRoot: LH.baseApiUrl + '/invoices',

        dateFormat: 'dd.mm.yy',
        datePrintFormat: "dd.mm.yyyy",
        timeFormat: 'HH:mm',
        invalidMessage: 'Вы ввели неверную дату',

        saveFields: [
            'sku',
            'supplier',
            'acceptanceDate',
            'accepter',
            'legalEntity',
            'supplierInvoiceSku',
            'supplierInvoiceDate'
        ]
    });
});