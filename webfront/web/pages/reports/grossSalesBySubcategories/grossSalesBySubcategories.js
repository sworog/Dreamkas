define(function(require, exports, module) {
    //requirements
    var Page = require('kit/core/page');

    require('jquery');

    return Page.extend({
        __name__: module.id,
        storeId: null,
        categoryId: null,
        partials: {
            '#content': require('tpl!./content.html')
        },
        initialize: function(){
            var page = this;

            page.render();
        }
    });
});