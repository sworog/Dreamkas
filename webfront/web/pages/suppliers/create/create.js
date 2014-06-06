define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    require('jquery');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            localNavigation: require('rv!pages/suppliers/localNavigation.html')
        },
        components: {
            form_supplier: require('blocks/form/form_supplier/form_supplier')
        }
    });
});