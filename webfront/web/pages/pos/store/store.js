define(function(require, exports, module) {
    //requirements
    var Page_pos = require('blocks/page/pos/pos'),
        cookies = require('cookies');

    return Page_pos.extend({
		title: 'Касса',
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'store',
        initialize: function(){
            var page = this;

            cookies.set('posStoreId', page.params.storeId);

            Page_pos.prototype.initialize.apply(page, arguments);
        },
        models: {
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