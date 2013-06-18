define(function(require) {
    //requirements
    var Page = require('kit/page');

    return Page.extend({
        templates: {
            content: require('tpl!./templates/dashboard.html')
        }
    });
});