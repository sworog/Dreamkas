define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    require('jquery');

    return Page.extend({
        partials: {
            content: require('ejs!./content.ejs'),
            localNavigation: require('ejs!blocks/localNavigation/localNavigation_suppliers.ejs')
        },
        models: {
            supplier: require('models/supplier')
        },
        blocks: {
            form_supplier: require('blocks/form/form_supplier/form_supplier')
        }
    });
});