define(function(require) {
        //requirements
        var Collection = require('kit/collection/collection'),
			uri = require('uri'),
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
			},

            fetch: function(){

                return Collection.prototype.fetch.call(this, {
                    data: {
                        dateTo: formatDate(moment(this.filters.dateTo, 'DD.MM.YYYY').add(1, 'days'))
                    }
                });
            }
        });
    }
);