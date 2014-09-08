define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        models: {
            store: function(){
                var page = this,
                    StoreModel = require('models/store/store');

                return new StoreModel({
                    id: page.params.storeId
                });
            },
            receipt: require('models/receipt/receipt')
        },
        blocks: {
            productFinder: require('blocks/productFinder/productFinder'),
            receipt: require('blocks/receipt/receipt'),
            modal_receiptProduct: require('blocks/modal/receiptProduct/receiptProduct')
        }
    });
});