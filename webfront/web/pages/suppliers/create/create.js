define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        Form_supplier = require('blocks/form/form_supplier/form_supplier');

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
        },
        render: function(){
            var page = this;

            Page.prototype.render.apply(page, arguments);

            page.blocks = {
                form_supplier: new Form_supplier()
            }
        }
    });
});