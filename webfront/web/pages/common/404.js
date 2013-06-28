define(function(require) {
    //requirements
    var Page = require('pages/page');

    return Page.extend({
        pageName: 'page_common_404',
        templates: {
            '#content': require('tpl!./templates/404.html')
        }
    });
});