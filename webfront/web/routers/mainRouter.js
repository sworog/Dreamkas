define(function(require) {
        //requirements
        var content = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

        require('routers/invoice');
        require('routers/balance');
        require('routers/product');
        require('routers/writeOff');
        require('routers/catalog');
        require('routers/user');

        var Router = BaseRouter.extend({
            routes: {
                '': 'dashboard',
                '/': 'dashboard',
                'dashboard': 'dashboard',
                'sale': 'sale'
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
