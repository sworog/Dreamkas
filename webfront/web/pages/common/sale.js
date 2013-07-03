define(function(require) {
    //requirements
    var Page = require('pages/page'),
        SaleBox = require('blocks/saleBox/saleBox');

    return Page.extend({
        pageName: 'page_common_sale',
        templates: {
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