define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        ProductModel = require('models/product');

    return Page.extend({
        params: {

        },
        templates: {
            content: require('tpl!./content.html'),
            localNavigation: require('tpl!pages/product/templates/localNavigation.html')
        },
        localNavigationActiveLink: 'barcodes',
        models: {
            product: function(){
                var page = this;

                return
            }
        },
        collections: {

        },
        blocks: {

        }
    });
});