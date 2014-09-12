define(function(require, exports, module) {
    //requirements
    var PosPart = require('pages/pos/part/part');

    return PosPart.extend({
		title: 'Касса',
        content: require('ejs!./content.ejs'),
		activeNavigationItem: 'store',
        models: {
            store: PosPart.prototype.models.store,
            receipt: require('models/receipt/receipt')
        },
        blocks: {
            productFinder: require('blocks/productFinder/productFinder'),
            form_receipt: require('blocks/form/receipt/receipt'),
            modal_receiptProduct: require('blocks/modal/receiptProduct/receiptProduct')
        }
    });
});