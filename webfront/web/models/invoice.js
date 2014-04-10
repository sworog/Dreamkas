define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        InvoiceProductsCollection = require('collections/invoiceProducts');

    return Model.extend({
        storeId: null,
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + this.storeId + '/invoices'
        },
        defaults: {
            includesVAT: true,
            collections: {
                products: new InvoiceProductsCollection()
            }
        },
        saveData: [
            'supplier',
            'acceptanceDate',
            'accepter',
            'legalEntity',
            'includesVAT',
            'supplierInvoiceNumber',
            'products'
        ],
        parse: function(data) {

            data.collections = {
                products: new InvoiceProductsCollection(data.products)
            };

            return data;
        }
    });
});