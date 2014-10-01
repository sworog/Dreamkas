define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('kit/collectionBlock/collectionBlock');

    return CollectionBlock.extend({
        template: require('ejs!./submitButton.ejs'),
        collection: require('collections/refundProducts/refundProducts'),
        calculateTotalPrice: function() {
            var block = this,
                totalPrice = 0;

            block.collection.forEach(function(refundProductModel) {
                totalPrice += block.normalizeNumber(refundProductModel.get('quantity')) * block.normalizeNumber(refundProductModel.get('price'));
            });

            return block.formatMoney(totalPrice);
        }
    });
});