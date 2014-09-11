define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page'),
        cookies = require('cookies');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        initialize: function(){
            var page = this;

            cookies.set('posStoreId', page.params.storeId);

            Page.prototype.initialize.apply(page, arguments);
        },
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
            modal_receiptProduct: require('blocks/modal/receiptProduct/receiptProduct'),
            modal_receipt: require('blocks/modal/receipt/receipt')
        }
    });
});