define(
    [
        '/views/pages/main.js'
    ],
    function(page) {
        var Router = Backbone.Router.extend({
            routes: {
//                "invoice": "invoiceList",
//                "invoice/list": "invoiceList",
//                "invoice/create": "invoiceCreate",
//                "invoice/edit/:id": "invoiceEdit",
                "invoice/view/:id": "invoiceView",
//
//                "products": "productList",
//                "product/list": "productList",
//                "product/edit": "productEdit",
                "product/edit/:id": "productEdit",
                "product/create": "productCreate",
                "product/view/:id": "productView",
                'balance': 'balance',


                "": "dashboard",
                "/": "dashboard",
                "dashboard": "dashboard"
            },
            dashboard: function() {
                page.open('/views/pages/dashboard.html');
            },
            balance: function() {
                page.open('/views/pages/balance.html');
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
                    productId: id
                });
            },
            productCreate: function(){
                page.open('/views/pages/product/create.html');
            },
            productView: function(id){
                page.open('/views/pages/product/view.html', {
                    id: id
                });
            }
        });

        return new Router();
    });
