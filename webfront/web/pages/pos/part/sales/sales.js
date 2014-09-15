define(function(require, exports, module) {
    //requirements
    var PosPart = require('pages/pos/part/part');

    return PosPart.extend({
		title: 'История продаж',
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'sales',
		models: {
			store: PosPart.prototype.models.store
		},
		collections: {
			receipts: function() {
				var page = this,
					ReceiptsCollection = require('collections/receipts/receipts'),
					filters,
					receipts;

				filters = _.pick(page.params, 'dateFrom', 'dateTo', 'product');

				receipts = new ReceiptsCollection([], {
					storeId: this.params.storeId,
					filters: filters
				});

				this.listenTo(receipts, {
					reset: function() {
						page.setParams(receipts.filters, true);
					}
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
		}
    });
});