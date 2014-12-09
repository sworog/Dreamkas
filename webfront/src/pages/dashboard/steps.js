define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./steps.ejs'),
        posUrl: function(){
            var block = this,
                stores = _.filter(block.resources.firstStart.get('stores'), function(storeItem) {
                return storeItem.inventoryCostOfGoods;
            });

            return '/pos' + (stores.length == 1 ? '/stores/' + stores[0].store.id : '') + '?firstStart=1';
        }
    });
});