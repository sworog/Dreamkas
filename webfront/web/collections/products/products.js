define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('models/product/product'),
            groupId: null,
            url: function(){
                return Collection.baseApiUrl + '/subcategories/' + this.groupId + '/products'
            },
            find: function(query){
                var collection = this;

                collection.query = query;

                collection.searchRequest && collection.searchRequest.abort();

                collection.searchRequest = $.ajax({
                    url: Collection.baseApiUrl + '/products/search',
                    data: {
                        properties: ['name', 'sku'],
                        query: collection.query
                    }
                });

                return collection.searchRequest.then(function(data){
                    collection.reset(data);
                });
            }
        });
    }
);