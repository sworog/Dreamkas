define(function(require) {
        //requirements
        var content_main = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

        require('routers/invoice');
        require('routers/balance');
        require('routers/product');
        require('routers/writeOff');
        require('routers/catalog');

        var Router = BaseRouter.extend({
            routes: {
                '': 'dashboard',
                '/': 'dashboard',
                'dashboard': 'dashboard',
                'sale': 'sale'
            },
            dashboard: function() {
                content_main.load('/pages/dashboard.html');
            },
            sale: function() {
                content_main.load('/pages/sale.html');
            }
        });

        return new Router();
    });
