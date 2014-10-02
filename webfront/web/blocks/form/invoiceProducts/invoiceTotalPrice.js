define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('kit/collectionBlock/collectionBlock');

    return CollectionBlock.extend({
        template: require('ejs!./invoiceTotalPrice.ejs'),
        calculateTotalPrice: function(){
            var block = this,
                totalSum = 0;

            block.collection.forEach(function(productModel){
                totalSum += productModel.get('totalPrice');
            });

            return block.formatMoney(totalSum);
        }
    });
});