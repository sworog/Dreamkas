define(function(require) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        pageName: 'page_common_dashboard',
        templates: {
            '#content': require('tpl!./templates/dashboard.html')
        }
    });
});