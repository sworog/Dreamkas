define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        params: {
            supplierId: null
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_suppliers.ejs')
        },
        models: {
            supplier: function(){
                var page = this,
                    Model = require('models/supplier');

                return new Model({
                    id: page.get('params.supplierId')
                });
            }
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