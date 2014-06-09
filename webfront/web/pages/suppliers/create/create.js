define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    require('jquery');

    return Page.extend({
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_suppliers.ejs')
        },
        models: {
            supplier: require('models/supplier')
        },
        blocks: {
            form_supplier: function(){
                var page = this,
                    Form_supplier = require('blocks/form/form_supplier/form_supplier');

                return new Form_supplier({
                    model: page.models.supplier
                });
            }
        }
    });
});