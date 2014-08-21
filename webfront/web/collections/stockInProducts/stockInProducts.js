define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection'),
            StockInProductModel = require('models/stockInProduct/stockInProduct'),
            cookies = require('cookies');

        return Collection.extend({
            model: StockInProductModel,
            validateProduct: function(product){
                var url = Collection.baseApiUrl + '/stockIns?validate=1&validationGroups=products';

                var stockInProductModel = new StockInProductModel(product);

                return $.ajax({
                    url: url,
                    dataType: 'json',
                    data: {
                        products: [stockInProductModel.getData()]
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