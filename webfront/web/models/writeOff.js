define(
    [
        './baseModel.js'
    ],
    function(baseModel) {
    return baseModel.extend({
        modelName: 'invoice',
        urlRoot: baseApiUrl + '/invoices',

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