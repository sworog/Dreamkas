define(function(require) {
        //requirements
        var content = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

        require('routers/invoice');
        require('routers/writeOff');
        require('routers/catalog');

        var Router = BaseRouter.extend({
            routes: {
                '': require('pages/common/dashboard'),
                '/': require('pages/common/dashboard'),
                'dashboard': require('pages/common/dashboard'),
                'sale': 'sale',
                'balance': require('pages/common/balance'),

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
