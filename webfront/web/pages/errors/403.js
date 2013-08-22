define(function(require) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        __name__: 'page_error_403',
        templates: {
            '#content': require('tpl!./templates/403.html')
        }
    });
});