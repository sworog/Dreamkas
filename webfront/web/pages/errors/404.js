define(function(require) {
    //requirements
    var Page = require('kit/core/page');

    return Page.extend({
        __name__: 'page_error_404',
        templates: {
            '#content': require('tpl!./templates/404.html')
        }
    });
});