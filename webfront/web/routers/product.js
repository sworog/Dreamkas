define(function(require) {
        //requirements
        var content_main = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

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
                content_main.load('/pages/product/list.html');
            },
            productView: function(productId){
                content_main.load('/pages/product/view.html', {
                    productId: productId
                });
            },
            productCreate: function(){
                content_main.load('/pages/product/create.html');
            },
            productEdit: function(productId){
                content_main.load('/pages/product/edit.html', {
                    productId: productId
                });
            }
        });

        return new Router();
    }
);