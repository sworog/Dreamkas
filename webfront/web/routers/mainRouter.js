define(function(require) {
        //requirements
        var content = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

        require('routers/invoice');
        require('routers/balance');
        require('routers/product');
        require('routers/writeOff');

        var Router = BaseRouter.extend({
            routes: {
                '': require('pages/main/dashboard'),
                '/': require('pages/main/dashboard'),
                'dashboard': require('pages/main/dashboard'),
                'sale': 'sale',

                //catalog
                'catalog': require('pages/catalog/catalog'),
                'catalog/:catalogClassId': require('pages/catalog/class'),
                'catalog/:catalogClassId/:catalogGroupId': require('pages/catalog/group'),

                //users
                'users': require('pages/user/list'),
                'user/edit/:userId': require('pages/user/form'),
                'user/create': require('pages/user/form'),
                'users/:userId': require('pages/user/view')

            },
            sale: function() {
                content.load('pages/sale.html');
            }
        });

        return new Router();
    });
