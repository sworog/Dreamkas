define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        moment = require('moment');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        events: {
            'click .receiptFinder__resultLink': function(e) {
                e.preventDefault();

                $(e.currentTarget)
                    .addClass('receiptFinder__resultLink_active')
                    .siblings('.receiptFinder__resultLink')
                    .removeClass('receiptFinder__resultLink_active');

                this.trigger('click:receipt', e.currentTarget.dataset.receiptId);
            }
        },
        models: {
            product: function() {
                return PAGE.models.product;
            }
        },
        collections: {
            receipts: function() {
                return PAGE.collections.receipts;
            }
        },
		blocks: {
			product_autocomplete: function() {
				var block = this,
					ProductAutocomplete = require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
					productAutocomplete;

                productAutocomplete = new ProductAutocomplete({
                    value: block.models.product.get('name')
                });

                productAutocomplete.$el.on('typeahead:selected', function(e, product) {
                    block.models.product.set(product);
                    block.findReceipts(block.$('.tt-input.form-control'));
                });

                productAutocomplete.on('input:clear', function(e, product) {
                    block.models.product.clear();
                    block.findReceipts(block.$('.tt-input.form-control'));
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
            var block = this,
                dateFrom = this.$el.find('.inputDateRange input[name="dateFrom"]').val(),
                dateTo = this.$el.find('.inputDateRange input[name="dateTo"]').val();

            if (!dateFrom || !dateTo) {
                return;
            }

            $(input).addClass('loading');

            block.collections.receipts.fetch({
                filters: {
                    dateFrom: dateFrom,
                    dateTo: block.formatDate(moment(dateTo, 'DD.MM.YYYY').add(1, 'days')),
                    product: block.models.product.get('id')
                }
            }).then(function() {

                PAGE.setParams({
                    dateFrom: dateFrom,
                    dateTo: dateTo,
                    product: block.models.product.get('id')
                });

                $(input).removeClass('loading');
            });
        }
    });
});