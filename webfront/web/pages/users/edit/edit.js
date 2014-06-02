define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        partials: {
            content: require('rv!./content.html')
        },
        components: {
            form_user: require('blocks/form/form_user/form_user')
        }
    });
});