define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('models/product/product'),
			storeId: null,
            groupId: null,
            searchQuery: null,
            url: function(){
                return Collection.baseApiUrl + '/subcategories/' + this.groupId + '/products'
            },
            find: function(query){
                var url = Collection.baseApiUrl + '/products/search';

				return this.findByUrl(url, query);
            },
			findByStore: function(query) {
				var url = Collection.baseApiUrl + '/stores/' + this.storeId + '/products';

				return this.findByUrl(url, query);
			},
			findByUrl: function(url, query) {
				var collection = this;

				collection.searchQuery = query;

				collection.searchRequest && collection.searchRequest.abort();

				collection.searchRequest = $.ajax({
					url: url,
					data: {
						properties: ['name', 'sku', 'barcode'],
						query: collection.searchQuery
					}
				});

				return collection.searchRequest.then(function(data){
					collection.reset(data);
				});
			}
        });
    }
);