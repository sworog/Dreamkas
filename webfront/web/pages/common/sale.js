define(function(require) {
    //requirements
    var Page = require('kit/core/page.deprecated'),
        SaleBox = require('blocks/saleBox/saleBox');

    return Page.extend({
        __name__: 'page_common_sale',
        partials: {
            '#content': require('tpl!./templates/sale.html')
        },
        permissions: {
            purchases: 'POST'
        },
        initialize: function(){
            var page = this;

            page.render();

            new SaleBox({
                el: document.getElementById('saleBox')
            });
        }
    });
});