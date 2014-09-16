define(function(require, exports, module) {
	//requirements
	var Block = require('kit/block/block');

	return Block.extend({
		template: require('ejs!./template.ejs'),
		events: {
			'hide .inputDateRange input[name="dateFrom"]': function(e) {
				this.findReceipts(e.currentTarget);
			},
			'hide .inputDateRange input[name="dateTo"]': function(e) {
				this.findReceipts(e.currentTarget);
			}
		},
		models: {
			product: function() {
				return this.product;
			}
		},
		collections: {
			receipts: function() {
				return this.receipts;
			}
		},
		blocks: {
			productAutocomplete: function(params) {
				var block = this,
					product = this.models.product,
					ProductAutocomplete = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
					productAutocomplete,
					input = block.$el.find('.autocomplete .form-control');

				params.resetLink = true;
				if (product) {
					params.value = product.get('name');
				}

				productAutocomplete = new ProductAutocomplete(params);
				productAutocomplete.$el.on('typeahead:selected', function(e, product) {
					var Product = require('models/product/product');

					block.models.product = new Product(product);
					block.findReceipts(input);
				});
				productAutocomplete.on('input:clear', function(e, product) {
					block.models.product = null;
					block.findReceipts(input);
				});

				return productAutocomplete;
			},
			inputDateRange: require('blocks/inputDateRange/inputDateRange'),
			receiptFinder__results: function(params) {
				var ReceiptFinderResults = require('./receiptFinder__results');

				params.collection = this.collections.receipts;

				return new ReceiptFinderResults(params);
			}
		},
		findReceipts: function(input) {
			var dateFrom = this.$el.find('.inputDateRange input[name="dateFrom"]').val(),
				dateTo = this.$el.find('.inputDateRange input[name="dateTo"]').val(),
				product;

			if (!dateFrom || !dateTo) {
				return;
			}

			if (this.models.product) {
				product = this.models.product.get('id');
			}

			$(input).addClass('loading');

			this.collections.receipts.find(dateFrom, dateTo, product).then(function() {
				$(input).removeClass('loading');
			});
		}
	});
});