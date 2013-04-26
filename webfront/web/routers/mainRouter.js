define(
    [
        '/pages/page.js',
        './invoiceRoutes.js',
        './productRoutes.js'
    ],
    function(page, invoiceRoutes, productRoutes) {

        var routes = _.extend({
            "": "dashboard",
            "/": "dashboard",
            "dashboard": "dashboard"
        }, invoiceRoutes, productRoutes);

        var Router = Backbone.Router.extend({
            routes: routes,
            dashboard: function() {
                page.open('/pages/dashboard.html');
            }
        });

        return new Router();
    });
