define(function(require, exports, module) {
    //requirements
    var Page_pos = require('blocks/page/pos/pos');

    return Page_pos.extend({
        title: 'История продаж',
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'sales',
        globalEvents: {
            'click:receipt': function(receiptId){
                this.setParams({
                    receiptId: receiptId
                });
            }
        },
        models: {
            product: function() {
                var Product = require('resources/product/model');

                return new Product({
                    id: this.params.product
                });
            }
        },
        collections: {
            receipts: function() {
                var page = this,
                    ReceiptsCollection = require('resources/receipt/collection');

                return new ReceiptsCollection([], {
                    storeId: this.params.storeId,
                    filters: _.pick(page.params, 'dateFrom', 'dateTo', 'product')
                });
            }
        },

        blocks: {
            receiptFinder: require('blocks/receiptFinder/receiptFinder'),
            sale: require('blocks/sale/sale')
        }
    });
});