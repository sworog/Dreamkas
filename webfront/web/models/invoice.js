define(
    [
        './baseModel.js'
    ],
    function(baseModel) {
    return baseModel.extend({
        modelName: 'invoice',
        url: function(){
            return baseApiUrl + '/invoices/' + this.id + '.json'
        },

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