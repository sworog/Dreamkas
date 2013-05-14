define(
    [
        '/pages/page.js',
        './baseRouter.js',
        './invoice.js',
        './balance.js',
        './product.js'
    ],
    function(page, BaseRouter) {

        var Router = BaseRouter.extend({
            routes: {
                "": "dashboard",
                "/": "dashboard",
                "dashboard": "dashboard"
            },
            dashboard: function() {
                page.open('/pages/dashboard.html');
            }
        });

        return new Router();
    });
