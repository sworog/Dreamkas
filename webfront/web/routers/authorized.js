define(function(require) {
    //requirements
    var BaseRouter = require('routers/baseRouter');

    var Router = BaseRouter.extend({
        routes: {
            '': require('pages/common/dashboard'),
            '/': require('pages/common/dashboard'),
            'dashboard(/)': require('pages/common/dashboard'),
            'sale(/)': require('pages/common/sale'),
            'balance(/)': require('pages/common/balance'),

            'invoices(/)': require('pages/invoice/list'),
            'invoices/create(/)': require('pages/invoice/form'),
            'invoices/:invoiceId(/)': require('pages/invoice/view'),

            'users(/)': require('pages/user/list'),
            'users/edit/:userId(/)': require('pages/user/form'),
            'users/create(/)': require('pages/user/form'),
            'users/:userId(/)': require('pages/user/view'),

            'products(/)': require('pages/product/list'),
            'products/edit/:productId(/)': require('pages/product/form'),
            'products/create(/)': require('pages/product/form'),
            'products/:productId(/)': require('pages/product/view'),

            'writeOffs(/)': require('pages/writeOff/list'),
            'writeOffs/create(/)': require('pages/writeOff/form'),
            'writeOffs/:writeOffId(/)': require('pages/writeOff/view'),

            'catalog': require('pages/catalog/catalog'),
            'catalog/:catalogClassId': require('pages/catalog/class'),

            '*path': require('pages/common/404')
        }
    });

    return new Router();
});
