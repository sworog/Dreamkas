define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('models/receipt/receipt'),
			/*,
            url: function(){
                return Collection.baseApiUrl + '/subcategories/' + this.groupId + '/products'
            },
            find: function(query){
                var collection = this;

                collection.searchQuery = query;

                collection.searchRequest && collection.searchRequest.abort();

                collection.searchRequest = $.ajax({
                    url: Collection.baseApiUrl + '/products/search',
                    data: {
                        properties: ['name', 'sku', 'barcode'],
                        query: collection.searchQuery
                    }
                });

                return collection.searchRequest.then(function(data){
                    collection.reset(data);
                });
            }
            */
        });
    }
);