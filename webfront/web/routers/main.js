define(function(require) {
        //requirements
        var BaseRouter = require('routers/baseRouter');

        require('routers/writeOff');
        require('routers/catalog');

        var Router = BaseRouter.extend({
            routes: {
                //common
                '': require('pages/common/dashboard'),
                '/': require('pages/common/dashboard'),
                'dashboard': require('pages/common/dashboard'),
                'sale': require('pages/common/sale'),
                'balance': require('pages/common/balance'),

                //invoices
                'invoices': require('pages/invoice/list'),
                'invoices/create': require('pages/invoice/form'),
                'invoices/:invoiceId': require('pages/invoice/view'),

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
            }
        });

        return new Router();
    });
