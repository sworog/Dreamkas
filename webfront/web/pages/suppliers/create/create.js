define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page'),
        Form_supplier = require('blocks/form/form_supplier/form_supplier'),
        SupplierModel = require('models/supplier'),
        currentUserModel = require('models/currentUser');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        partials: {
            '#content': require('tpl!./content.html')
        },
        permissions: function() {
            return !LH.isAllow('suppliers', 'POST');
        },
        initialize: function() {
            var page = this;

            page.render();

            new Form_supplier();
        }
    });
});