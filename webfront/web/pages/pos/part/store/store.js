define(function(require, exports, module) {
    //requirements
    var PosPart = require('pages/pos/part/part'),
        cookies = require('cookies');

    return PosPart.extend({
		title: 'Касса',
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'store',
        initialize: function(){
            var page = this;

            cookies.set('posStoreId', page.params.storeId);

            PosPart.prototype.initialize.apply(page, arguments);
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