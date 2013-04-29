define(
    [
        '/pages/page.js',
        './invoice.js',
        './balance.js',
        './product.js'
    ],
    function(page) {

        var Router = Backbone.Router.extend({
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
