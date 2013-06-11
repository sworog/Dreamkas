define(function(require) {
        //requirements
        var page = require('blocks/page/page'),
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
                page.open('/pages/dashboard.html');
            },
            sale: function() {
                page.open('/pages/sale.html');
            }
        });

        return new Router();
    });
