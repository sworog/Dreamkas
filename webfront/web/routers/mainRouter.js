define(function(require) {
        //requirements
        var content = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

        require('routers/invoice');
        require('routers/balance');
        require('routers/writeOff');

        var Router = BaseRouter.extend({
            routes: {
                '': require('pages/main/dashboard'),
                '/': require('pages/main/dashboard'),
                'dashboard': require('pages/main/dashboard'),
                'sale': 'sale',

                //catalog
                //'catalog': require('pages/catalog/catalog'),
                //'catalog/:catalogClassId': require('pages/catalog/class'),
                //'catalog/:catalogClassId/:catalogGroupId': require('pages/catalog/group'),

                //users
                'users': require('pages/user/list'),
                'users/edit/:userId': require('pages/user/form'),
                'users/create': require('pages/user/form'),
                'users/:userId': require('pages/user/view'),

                //products
                'products': require('pages/product/list'),
                'products/edit/:productId': require('pages/product/form'),
                'products/create': require('pages/product/form'),
                'products/:productId': require('pages/product/view')
            },
            sale: function() {
                content.load('pages/sale.html');
            }
        });

        return new Router();
    });
