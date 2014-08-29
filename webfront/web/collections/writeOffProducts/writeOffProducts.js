define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection'),
            WriteOffProductModel = require('models/writeOffProduct/writeOffProduct'),
            cookies = require('cookies');

        return Collection.extend({
            model: WriteOffProductModel,
            validateProduct: function(product){
                var url = Collection.baseApiUrl + '/writeOffs?validate=1&validationGroups=products';

                var writeOffProductModel = new WriteOffProductModel(product);

                return $.ajax({
                    url: url,
                    dataType: 'json',
                    data: {
                        products: [writeOffProductModel.getData()]
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