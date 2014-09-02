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
            productFinder: function(opt){
                var block = this,
                    ProductFinder = require('blocks/productFinder/productFinder');

                return new ProductFinder({
                    el: opt.el,
                    models: {
                        receipt: block.models.receipt
                    }
                });
            },
            form_receipt: function(opt){
                var block = this,
                    Form_receipt = require('blocks/form/receipt/receipt');

                return new Form_receipt({
                    el: opt.el,
                    model: block.models.receipt
                })
            }
        }
    });
});