define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection'),
            SupplierReturnProductModel = require('models/supplierReturnProduct/supplierReturnProduct'),
            cookies = require('cookies');

        return Collection.extend({
            model: SupplierReturnProductModel,
            validateProduct: function(product){
                var url = Collection.baseApiUrl + '/supplierReturns?validate=1&validationGroups=products';

                var supplierReturnProductModel = new SupplierReturnProductModel(product);

                return $.ajax({
                    url: url,
                    dataType: 'json',
                    data: {
                        products: [supplierReturnProductModel.getData()]
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