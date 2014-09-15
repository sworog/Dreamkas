define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection');

        return Collection.extend({
            model: require('models/receipt/receipt'),
            url: function(){
                return Collection.baseApiUrl + '/store/' + this.storeId + '/sales';
            },
            find: function(dateFrom, dateTo){
                var collection = this;

                collection.dateFrom = dateFrom;
				collection.dateTo = dateTo;

                collection.searchRequest && collection.searchRequest.abort();

                collection.searchRequest = $.ajax({
                    url: this.url(),
					data: this.data()
                });

                return collection.searchRequest.then(function(data){
                    collection.reset(data);
                });
            },
			data: function() {
				return {
					dateFrom: this.dateFrom,
					dateTo: this.dateTo,
					product: ''
				};
			}
        });
    }
);