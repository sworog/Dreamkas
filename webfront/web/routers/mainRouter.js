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
