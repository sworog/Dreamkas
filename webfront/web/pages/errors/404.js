define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated');

    return Page.extend({
        __name__: 'page_error_404',
        partials: {
            '#content': require('tpl!./templates/404.html')
        }
    });
});