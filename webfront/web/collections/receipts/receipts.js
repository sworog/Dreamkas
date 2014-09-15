define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection'),
			uri = require('uri'),
			moment = require('moment'),
			formatDate = require('kit/formatDate/formatDate');

        return Collection.extend({
			initialize: function(data, options){
				options = options || {};

				options.filters = _.extend(this.defaultDateRange(), options.filters);

				Collection.prototype.initialize.call(this, data, options);
			},
            model: require('models/receipt/receipt'),
			filters: {
				product: null
			},
			defaultDateRange: function() {
				var currentTime = Date.now();

				return {
					dateFrom: formatDate(moment(currentTime).subtract(1, 'week')),
					dateTo: formatDate(currentTime)
				};
			},
			url: function() {
				return uri(Collection.baseApiUrl + '/stores/' + this.storeId + '/sales').query(this.filters).toString();
			},
            find: function(dateFrom, dateTo, product){
                var collection = this;

                collection.filters.dateFrom = dateFrom;
				collection.filters.dateTo = dateTo;
				collection.filters.product = product;

                collection.searchRequest && collection.searchRequest.abort();

                collection.searchRequest = $.ajax({
                    url: this.url()
                });

                return collection.searchRequest.then(function(data){
                    collection.reset(data);
                });
            }
        });
    }
);