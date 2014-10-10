define(function(require, exports, module) {
    //requirements
    var Page_pos = require('blocks/page/pos/pos'),
        moment = require('moment');

    return Page_pos.extend({
        title: 'История продаж',
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'sales',
        params: {
            dateTo: function(){
                var page = this,
                    currentTime = Date.now();

                return page.formatDate(moment(currentTime));
            },
            dateFrom: function(){
                var page = this,
                    currentTime = Date.now();

                return page.formatDate(moment(currentTime).subtract(1, 'week'));
            }
        },
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
                    filters: {
                        dateFrom: page.params.dateFrom,
                        dateTo: page.formatDate(moment(page.params.dateTo, 'DD.MM.YYYY').add(1, 'days')),
                        product: page.params.product
                    }
                });
            }
        },

        initData: function(){

            this.params.dateTo = this.get('params.dateTo');
            this.params.dateFrom = this.get('params.dateFrom');

            return Page_pos.prototype.initData.apply(this, arguments);
        },

        blocks: {
            receiptFinder: require('blocks/receiptFinder/receiptFinder'),
            sale: require('blocks/sale/sale')
        }
    });
});