define(function(require) {
        //requirements
        var content = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

        require('routers/invoice');
        require('routers/balance');
        require('routers/product');
        require('routers/writeOff');
        require('routers/catalog');

        var Router = BaseRouter.extend({
            routes: {
                '': require('pages/main/dashboard'),
                '/': require('pages/main/dashboard'),
                'dashboard': require('pages/main/dashboard'),
                'sale': 'sale',

                //users
                'users': require('pages/user/list'),
                'user/edit/:userId': require('pages/user/form'),
                'user/create': require('pages/user/form'),
                'users/:userId': require('pages/user/view')

            },
            dashboard: function() {
                content.load('pages/dashboard.html');
            },
            sale: function() {
                content.load('pages/sale.html');
            }
        });

        return new Router();
    });
