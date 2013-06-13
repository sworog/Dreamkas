define(function(require) {
        //requirements
        var content_main = require('blocks/content/content_main'),
            BaseRouter = require('routers/baseRouter');

        var Router = BaseRouter.extend({
            routes: {
                'balance': 'balance'
            },
            balance: function() {
                content_main.load('/pages/balance.html');
            }
        });

        return new Router();
    }
);