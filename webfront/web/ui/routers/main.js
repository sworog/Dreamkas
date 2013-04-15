define(
    [
        '/ui/views/page.js'
    ],
    function(page) {
        var Router = Backbone.Router.extend({
            routes: {
                "": "dashboard",
                "/": "dashboard",
                "dashboard": "dashboard",

                "invoices": "invoicesList",
                "invoice/list": "invoicesList",
                "invoice/edit": "invoicesEdit",
                "invoice/edit/:id": "invoicesEdit",

                "products": "productList",
                "product/list": "productList",
                "product/edit": "productEdit",
                "product/edit/:id": "productEdit"
            },
            dashboard: function() {
                page.open('/views/pages/dashboard.html');
            },
            invoicesList: function(){
                page.open('/views/pages/invoice/list.html');
            },
            invoicesEdit: function(id){
                page.open('/views/pages/invoice/edit.html', {
                    id: id
                });
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