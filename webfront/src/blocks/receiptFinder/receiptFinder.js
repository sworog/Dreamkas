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
            },
            'select .autocomplete': function(e, product){

                var block = this;

                e.target.classList.add('loading');

                block.models.product.set(product);
                block.findReceipts();
            },
            'clear .autocomplete': function(e){

                var block = this;

                e.target.classList.add('loading');

                block.models.product.clear();
                block.findReceipts();
            },
            'update .inputDateRange': function(e){

                var block = this;

                e.target.classList.add('loading');

                block.findReceipts();
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
			product_autocomplete: require('blocks/autocomplete/autocomplete_products/autocomplete_products'),
            inputDateRange: require('blocks/inputDateRange/inputDateRange'),
            receiptFinder__results: require('./receiptFinder__results')
        },
        findReceipts: function() {
            var block = this,
                dateFrom = this.$el.find('.inputDateRange input[name="dateFrom"]').val(),
                dateTo = this.$el.find('.inputDateRange input[name="dateTo"]').val();

            if (!dateFrom || !dateTo) {
                return;
            }

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

                block.$('.loading').removeClass('loading');
            });
        }
    });
});