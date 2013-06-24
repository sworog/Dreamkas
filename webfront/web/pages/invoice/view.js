define(function(require) {
    //requirements
    var Page = require('pages/page'),
        Invoice = require('blocks/invoice/invoice'),
        content_main = require('blocks/content/content_main');

//    return Page.extend({
//        initialize: function(invoiceId, params){
//            var page = this,
//                params = params || {};
//
//            page.editMode = params.editMode;
//            page.invoiceId = invoiceId;
//        },
//        templates: {
//            '#content': require('tpl!./templates/view.html')
//        },
//        initModels: {
//
//        },
//        initBlocks: function(){
//            new Invoice();
//        }
//    });

    return function(invoiceId, params){
        content_main.load('/pages/invoice/templates/view.html', {
            invoiceId: invoiceId,
            params: params || {}
        });
    };
});