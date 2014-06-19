define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        params: {
            productId: null
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_product.ejs')
        },
        models: {
            product: function(){
                var page = this,
                    ProductModel = require('models/product');

                return new ProductModel({
                    id: page.params.productId
                })
            }
        },
        blocks: {
            form_barcodes: require('blocks/form/form_barcodes/form_barcodes'),
            form_barcode: require('blocks/form/form_barcode/form_barcode')
        }
    });
});