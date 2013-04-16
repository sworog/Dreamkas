define(function() {
    return Backbone.Model.extend({
        url: function(){
            return baseApiUrl + '/invoices/' + this.id + '.json'
        },

        dateFormat: 'dd.mm.yy',
        datePrintFormat: "dd.mm.yyyy",
        timeFormat: 'HH:mm',
        invalidMessage: 'Вы ввели неверную дату',

        defaults: {
            id: null,
            sku: null,
            supplier: null,
            acceptanceDate: null,
            accepter: null,
            legalEntity: null,
            supplierInvoiceSku: null,
            supplierInvoiceDate: null,
            sumTotal: null
        },

        excludeSaveFields: [
            'sumTotal'
        ]
    });
});