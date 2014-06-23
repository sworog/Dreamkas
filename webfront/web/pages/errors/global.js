define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated');

    return Page.extend({
        __name__: 'page_error_global',
        response: null,
        partials: {
            '#content': require('ejs!./templates/global.html')
        }
    });
});