define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            localNavigation: require('rv!pages/suppliers/localNavigation.html')
        },
        resources: {
            supplier: function(){
                var page = this,
                    Model = require('models/supplier');

                return new Model({
                    id: page.get('params.supplierId')
                });
            }
        },
        components: {
            'form-supplier': require('blocks/form/form_supplier/form_supplier')
        }
    });
});