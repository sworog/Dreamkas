define(function(require, exports, module) {
    //requirements
    var Page = require('pages/page');

    return Page.extend({
        partials: {
            content: require('rv!./content.html'),
            globalNavigation: require('rv!./globalNavigation.html')
        },
        components: {
            form_login: require('blocks/form/form_login/form_login')
        }
    });
});