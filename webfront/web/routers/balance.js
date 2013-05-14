define(
    [
        '/pages/page.js',
        './baseRouter.js'
    ],
    function(page, BaseRouter) {
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