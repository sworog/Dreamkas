define(function(require, exports, module) {
	//requirements
	var Block = require('kit/block/block');

	return Block.extend({
		template: require('ejs!./template.ejs'),
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
					productAutocomplete;

				params.resetLink = true;
				if (product) {
					params.value = product.get('name');
				}

				productAutocomplete = new ProductAutocomplete(params);
				productAutocomplete.$el.on('typeahead:selected', function(e, product) {
					var Product = require('models/product/product');

					block.models.product = new Product(product);
					block.findReceipts(block.$el.find('.autocomplete input.form-control'));
				});
				productAutocomplete.on('input:clear', function(e, product) {
					block.models.product = null;
					block.findReceipts(block.$el.find('.autocomplete input.form-control'));
				});

				return productAutocomplete;
			},
			dateRangeInput: function(params) {
				var block = this,
					DateRangeInput = require('blocks/inputDateRange/inputDateRange'),
					dateRangeInput = new DateRangeInput(params);

				dateRangeInput.on('change:values', function(data) {
					block.findReceipts(block.$el.find('.inputDateRange input'));
				});

				return dateRangeInput;
			},
			receiptFinder__results: function(params) {
				var ReceiptFinderResults = require('./receiptFinder__results');

				params.collection = this.collections.receipts;

				return new ReceiptFinderResults(params);
			}
		},
		findReceipts: function(inputs) {
			var dateRangeInput = this.__blocks.dateRangeInput[0],
				product;

			if (this.models.product) {
				product = this.models.product.get('id');
			}

			$(inputs).addClass('loading');

			this.collections.receipts.find(dateRangeInput.dateFrom, dateRangeInput.dateTo, product).then(function() {
				$(inputs).removeClass('loading');
			});
		}
	});
});