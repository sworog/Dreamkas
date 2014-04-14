define(function(require) {
    //requirements
    var Model = require('kit/core/model'),
        cookies = require('cookies'),
        InvoiceProductsCollection = require('collections/invoiceProducts');

    return Model.extend({
        storeId: null,
        urlRoot: function() {
            return LH.baseApiUrl + '/stores/' + this.storeId + '/invoices'
        },
        defaults: {
            supplier: null,
            acceptanceDate: moment().valueOf(),
            accepter: null,
            legalEntity: null,
            includesVAT: true,
            supplierInvoiceNumber: null,
            products: null
        },
        saveData: function(){
            return {
                supplier: this.get('supplier'),
                acceptanceDate: this.get('acceptanceDate'),
                accepter: this.get('accepter'),
                legalEntity: this.get('legalEntity'),
                includesVAT: this.get('includesVAT'),
                supplierInvoiceNumber: this.get('supplierInvoiceNumber'),
                products: this.get('products').map(function(productModel) {
                    return productModel.getData();
                })
            }
        },
        parse: function(data) {

            this.get('products').reset(data.products);

            delete data.products;

            return data;
        },
        check: function(data){
            return $.ajax({
                url: this.url() + '?validate=true',
                data: data,
                dataType: 'json',
                type: 'POST',
                headers: {
                    Authorization: 'Bearer ' + cookies.get('token')
                },
                success: function(){
                    console.log(arguments)
                },
                error: function(){
                    console.log(arguments)
                }
            });
        }
    });
});