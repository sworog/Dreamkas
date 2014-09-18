define(function(require, exports, module) {
    //requirements
    var Page_pos = require('blocks/page/pos/pos');

    return Page_pos.extend({
		title: 'История продаж',
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'sales',
		models: {
			product: function() {
				var Product = require('models/product/product'),
					product;

				product = new Product({
					id: this.params.product
				});

				return product;
			}
		},
		collections: {
			receipts: function() {
				var page = this,
					ReceiptsCollection = require('collections/receipts/receipts'),
					filters,
					receiptsCollection;

				filters = _.pick(page.params, 'dateFrom', 'dateTo', 'product');

				receiptsCollection = new ReceiptsCollection([], {
					storeId: this.params.storeId,
					filters: filters
				});

				this.listenTo(receiptsCollection, {
					reset: function() {
						page.setParams(receiptsCollection.filters, true);
					}
				});

				return receiptsCollection;
			}
		},

		blocks: {
			receiptFinder: function(params)
			{
				var page = this,
					ReceiptFinder = require('blocks/receiptFinder/receiptFinder'),
					receiptFinder;

				params.receipts = this.collections.receipts;
				if (this.models.product.id) {
					params.product = this.models.product;
				}

				receiptFinder = new ReceiptFinder(params);
				receiptFinder.on('click:receipt', function(receipt) {
					page.__blocks.sale[0].render({
						model: receipt
					});
				});

				return receiptFinder;
			},
			sale: require('blocks/sale/sale')
		}
    });
});