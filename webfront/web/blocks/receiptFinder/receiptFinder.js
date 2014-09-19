define(function(require, exports, module) {
	//requirements
	var Block = require('kit/block/block');

	return Block.extend({
		template: require('ejs!./template.ejs'),
		events: {
			'click .receiptFinder__resultLink': function(e) {
				e.preventDefault();
                this.trigger('click:receipt', e.currentTarget.dataset.receiptId);
			}
		},
		blocks: {
			product_autocomplete: function() {
				var block = this,
                    autocompleteInput = block.$('.autocomplete input.form-control'),
					ProductAutocomplete = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
					productAutocomplete;

				productAutocomplete = new ProductAutocomplete({
                    value: PAGE.models.product.get('name')
                });

				productAutocomplete.$el.on('typeahead:selected', function(e, product) {
					PAGE.models.product.set(product);
                    block.findReceipts(autocompleteInput);
				});

				productAutocomplete.on('input:clear', function(e, product) {
                    PAGE.models.product.clear();
					block.findReceipts(autocompleteInput);
				});

				return productAutocomplete;
			},
            inputDateRange: function(params) {
				var block = this,
					DateRangeInput = require('blocks/inputDateRange/inputDateRange'),
					dateRangeInput = new DateRangeInput(params);

				dateRangeInput.on('change:values', function(data) {
					block.findReceipts(block.$('.inputDateRange input'));
				});

				return dateRangeInput;
			},
			receiptFinder__results: require('./receiptFinder__results')
		},
        findReceipts: function(input) {
            var dateFrom = this.$el.find('.inputDateRange input[name="dateFrom"]').val(),
                dateTo = this.$el.find('.inputDateRange input[name="dateTo"]').val();

            if (!dateFrom || !dateTo) {
                return;
            }

            $(input).addClass('loading');

            PAGE.collections.receipts.filter({
                dateFrom: dateFrom,
                dateTo: dateTo,
                product: PAGE.models.product.get('id')
            }).then(function() {

                PAGE.setParams(PAGE.collections.receipts.filters, {
                    render: false
                });

                $(input).removeClass('loading');
            });
        }
	});
});