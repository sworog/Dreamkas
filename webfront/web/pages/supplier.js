define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        params: {
            supplierId: null
        },
        partials: {
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_supplier.ejs')
        },
        models: {
            supplier: function(){
                var page = this,
                    Model = require('models/supplier/supplier');

                return new Model({
                    id: page.get('params.supplierId')
                });
            }
        }

    });
});