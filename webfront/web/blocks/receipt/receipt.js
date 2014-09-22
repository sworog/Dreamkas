define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        formatMoney = require('kit/formatMoney/formatMoney'),
        normalizeNumber = require('kit/normalizeNumber/normalizeNumber');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        events: {
            'click .receipt__productLink': function(e) {
                document.getElementById('modal_receiptProduct').block.show({
                    models: {
                        receiptProduct: PAGE.models.receipt.collections.receiptProducts.get(e.currentTarget.dataset.receiptProductCid)
                    }
                });
            },
            'click .receipt__clearLink .confirmLink__confirmation': function(e) {
                var block = this;

                PAGE.models.receipt.collections.products.reset([]);
            }
        },
        blocks: {
            modal_receipt: require('blocks/modal/receipt/receipt')
        },
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.listenTo(PAGE.models.receipt.collections.receiptProducts, {
                'add remove change': function() {
                    block.render();
                    block.$('.receipt__scrollContainer').scrollTop(block.$('.receipt__scrollContainer table').height());
                }
            });
        },
        calculateItemPrice: function(receiptProductModel) {
            return formatMoney(normalizeNumber(receiptProductModel.get('quantity')) * normalizeNumber(receiptProductModel.get('price')));
        },
        calculateTotalPrice: function() {
            var block = this,
                totalPrice = 0;

            PAGE.models.receipt.collections.receiptProducts.forEach(function(receiptProductModel) {
                totalPrice += normalizeNumber(block.calculateItemPrice(receiptProductModel));
            });

            return formatMoney(totalPrice);
        }
    });
});