define(function(require) {
    //requirements
    var Page = require('pages/page'),
        SaleBox = require('blocks/saleBox/saleBox');

    return Page.extend({
        pageName: 'page_common_sale',
        templates: {
            '#content': require('tpl!./templates/sale.html')
        },
        initialize: function(){
            new SaleBox({
                el: document.getElementById('saleBox')
            });
        }
    });
});