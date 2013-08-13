define(function(require) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        pageName: 'page_error_403',
        templates: {
            '#content': require('tpl!./templates/403.html')
        }
    });
});