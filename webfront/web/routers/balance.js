define(
    [
        '/pages/page.js'
    ],
    function(page) {
        var Router = Backbone.Router.extend({
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