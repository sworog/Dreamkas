define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            globalNavigation: require('rv!pages/globalNavigation_main.html')
        },
        components: {
            form_store: require('blocks/form/form_store/form_store')
        }
    });
});