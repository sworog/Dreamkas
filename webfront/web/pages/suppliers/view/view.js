define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        Form_supplier = require('blocks/form/form_supplier/form_supplier'),
        SupplierModel = require('models/supplier'),
        currentUserModel = require('models/currentUser');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        params: {
            supplierId: null
        },
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function() {
            return !LH.isAllow('suppliers', 'PUT::{supplier}');
        },
        initialize: function() {
            var page = this;

            page.models = {
                supplier: new SupplierModel({
                    id: page.params.supplierId
                })
            };

            $.when(page.models.supplier.fetch()).done(function(){
                page.render();

                page.blocks = {
                    form_supplier: new Form_supplier({
                        model: page.models.supplier
                    })
                }
            });
        }
    });
});