define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            url: Collection.baseApiUrl + '/catalog/groups/reports/grossMarginSalesByCatalogGroup'
        });
    }
);