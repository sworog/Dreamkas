define(
    [
        '/kit/page.js',
        './baseRouter.js'
    ],
    function(page, BaseRouter) {

        var Router = BaseRouter.extend({
            routes: {
                'products': 'productList',
                'product/list': 'productList',
                'product/edit/:productId': 'productEdit',
                'product/create': 'productCreate',
                'product/view/:productId': 'productView',
                'product/:productId': 'productView'
            },
            productList: function(){
                page.open('/pages/product/list.html');
            },
            productView: function(productId){
                page.open('/pages/product/view.html', {
                    productId: productId
                });
            },
            productCreate: function(){
                page.open('/pages/product/create.html');
            },
            productEdit: function(productId){
                page.open('/pages/product/edit.html', {
                    productId: productId
                });
            }
        });

        return new Router();
    }
);