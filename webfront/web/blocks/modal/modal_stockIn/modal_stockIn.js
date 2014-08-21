define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        models: {
            stockIn: require('models/stockIn/stockIn')
        },
        collections: {
            stores: function(){
                return PAGE.collections.stores;
            }
        }
    });
});