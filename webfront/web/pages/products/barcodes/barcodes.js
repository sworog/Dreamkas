define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        ProductModel = require('models/product');

    return Page.extend({
        params: {
            productId: null
        },
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!pages/product/templates/localNavigation.html')
        },
        localNavigationActiveLink: 'barcodes',
        models: {
            product: function(){
                var page = this;

                return new ProductModel({
                    id: page.params.productId
                })
            }
        },
        collections: {

        },
        blocks: {

        }
    });
});