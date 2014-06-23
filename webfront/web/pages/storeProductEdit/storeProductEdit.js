define(function(require, exports, module) {
    //requirements
    var Page = require('pages/storeProduct');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs')
        },
        blocks: {
            form_storeProduct: function(){
                var page = this,
                    Form_storeProduct = require('blocks/form/form_storeProduct/form_storeProduct');

                return new Form_storeProduct({
                    model: page.models.product
                });
            }
        }
    });
});