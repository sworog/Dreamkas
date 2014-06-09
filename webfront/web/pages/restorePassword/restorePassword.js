define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            globalNavigation: require('rv!./globalNavigation.html')
        },
        components: {
            form_restorePassword: require('blocks/form/form_restorePassword/form_restorePassword')
        }
    });
});