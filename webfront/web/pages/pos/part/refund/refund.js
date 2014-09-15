define(function(require, exports, module) {
    //requirements
    var PosPart = require('pages/pos/part/part'),
		moment = require('moment'),
		formatDate = require('kit/formatDate/formatDate');

    return PosPart.extend({
		title: 'История продаж',
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'refund',
		models: {
			store: PosPart.prototype.models.store
		},
		collections: {
			receipts: function() {
				var ReceiptsCollection = require('collections/receipts/receipts'),
					dateRange = this.defaultDateRange(),
					receipts;

				receipts = new ReceiptsCollection([], {
					storeId: this.params.storeId,
					dateFrom: dateRange.dateFrom,
					dateTo: dateRange.dateTo
				});

				return receipts;
			}
		},
		blocks: {
			receiptFinder: function(params)
			{
				var ReceiptFinder = require('blocks/receiptFinder/receiptFinder'),
					receiptFinder;

				params.receipts = this.collections.receipts;

				receiptFinder = new ReceiptFinder(params);

				return receiptFinder;
			}
		},
		defaultDateRange: function() {
			var currentTime = Date.now();

			return {
				dateFrom: formatDate(moment(currentTime).subtract(1, 'week')),
				dateTo: formatDate(currentTime)
			}
		}
    });
});