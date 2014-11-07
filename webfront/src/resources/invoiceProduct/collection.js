define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection'),
            InvoiceProductModel = require('resources/invoiceProduct/model'),
            cookies = require('cookies');

        return Collection.extend({
            model: InvoiceProductModel,
            validateProduct: function(product){
                var url = Collection.baseApiUrl + '/invoices?validate=1&validationGroups=products';

                var invoiceProductModel = new InvoiceProductModel(product);

                return $.ajax({
                    url: url,
                    dataType: 'json',
                    data: {
                        products: [invoiceProductModel.getData()]
                    },
                    type: 'POST',
                    headers: {
                        Authorization: 'Bearer ' + cookies.get('token')
                    }
                })
            }
        });
    }
);