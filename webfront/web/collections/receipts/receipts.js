define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection'),
			uri = require('uri'),
			moment = require('moment'),
			formatDate = require('kit/formatDate/formatDate');

        return Collection.extend({
            model: require('models/receipt/receipt'),
            storeId: null,
			filters: function(){

                var currentTime = Date.now();

                return {
                    dateFrom: formatDate(moment(currentTime).subtract(1, 'week')),
                    dateTo: formatDate(moment(currentTime).add(1, 'days'))
                }
            },

			url: function() {
				return Collection.baseApiUrl + '/stores/' + this.storeId + '/sales';
			}
        });
    }
);