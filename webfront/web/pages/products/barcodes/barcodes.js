define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        Form_barcodes = require('blocks/form/form_barcodes/form_barcodes'),
        Form_barcode = require('blocks/form/form_barcode/form_barcode'),
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
        events: {
            'click .form_barcodes__removeLink': function(e){

                e.preventDefault();

                var link = e.target,
                    barcodesCollection = Page.current.models.product.collections.barcodes,
                    model = barcodesCollection.get(link.dataset.barcode_cid);

                barcodesCollection.remove(model);
            }
        },
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
            form_barcodes: Form_barcodes,
            form_barcode: Form_barcode
        },
        listeners: {
            'form_barcode:submit:success': function(data){
                console.log(data);
            }
        }
    });
});