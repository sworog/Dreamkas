define(function(require) {
    //requirements
    var Page = require('pages/page');

    return Page.extend({
        pageName: 'page_dashboard',
        templates: {
            '#content': require('tpl!./templates/dashboard.html')
        }
    });
});