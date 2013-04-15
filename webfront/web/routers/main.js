define(
    [
        '/views/page.js'
    ],
    function(page) {
        var Router = Backbone.Router.extend({
            routes: {
//                "invoice": "invoiceList",
//                "invoice/list": "invoiceList",
//                "invoice/create": "invoiceCreate",
//                "invoice/edit/:id": "invoiceEdit",
//                "invoice/view/:id": "invoiceView",
//
//                "products": "productList",
//                "product/list": "productList",
//                "product/edit": "productEdit",
//                "product/edit/:id": "productEdit",

                "": "dashboard",
                "/": "dashboard",
                "dashboard": "dashboard"
            },
            dashboard: function() {
                page.open('/views/pages/dashboard.html');
            },
            invoiceList: function(){
                page.open('/views/pages/invoice/list.html');
            },
            invoiceView: function(id){
                page.open('/views/pages/invoice/view.html', {
                    id: id
                });
            },
            invoiceEdit: function(id){
                page.open('/views/pages/invoice/edit.html', {
                    id: id
                });
            },
            invoiceCreate: function(){
                page.open('/views/pages/invoice/create.html');
            },
            productList: function(){
                page.open('/views/pages/product/list.html');
            },
            productEdit: function(id){
                page.open('/views/pages/product/edit.html', {
                    id: id
                });
            }
        });

        return new Router();
    });