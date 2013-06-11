define(function(require) {
        //requirements
        var page = require('blocks/page/page'),
            BaseRouter = require('routers/baseRouter');

        var Router = BaseRouter.extend({
            routes: {
                'balance': 'balance'
            },
            balance: function() {
                page.open('/pages/balance.html');
            }
        });

        return new Router();
    }
);