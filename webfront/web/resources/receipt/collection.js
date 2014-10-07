define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection'),
			uri = require('uri'),
            deepExtend = require('kit/deepExtend/deepExtend'),
			moment = require('moment'),
			formatDate = require('kit/formatDate/formatDate');

        return Collection.extend({
            model: require('resources/receipt/model'),
            storeId: null,
			filters: function(){

                var currentTime = Date.now();

                return {
                    dateFrom: formatDate(moment(currentTime).subtract(1, 'week')),
                    dateTo: formatDate(currentTime)
                }
            },

			url: function() {
				return Collection.baseApiUrl + '/stores/' + this.storeId + '/sales';
			}
        });
    }
);