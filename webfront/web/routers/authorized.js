define(function(require) {
    //requirements
    var BaseRouter = require('routers/baseRouter'),
        topBar = require('blocks/topBar/topBar');

    var Router = BaseRouter.extend({
        routes: {
            //common
            '': require('pages/common/dashboard'),
            '/': require('pages/common/dashboard'),
            'dashboard(/)': require('pages/common/dashboard'),
            'sale(/)': require('pages/common/sale'),
            'balance(/)': require('pages/common/balance'),

            //invoices
            'invoices(/)': require('pages/invoice/list'),
            'invoices/create(/)': require('pages/invoice/form'),
            'invoices/:invoiceId(/)': require('pages/invoice/view'),

            //users
            'users(/)': require('pages/user/list'),
            'users/edit/:userId(/)': require('pages/user/form'),
            'users/create(/)': require('pages/user/form'),
            'users/:userId(/)': require('pages/user/view'),

            //products
            'products(/)': require('pages/product/list'),
            'products/edit/:productId(/)': require('pages/product/form'),
            'products/create(/)': require('pages/product/form'),
            'products/:productId(/)': require('pages/product/view'),

            //writeOffs
            'writeOffs(/)': require('pages/writeOff/list'),
            'writeOffs/create(/)': require('pages/writeOff/form'),
            'writeOffs/:writeOffId(/)': require('pages/writeOff/view'),

            //catalog
            'catalog(/)': require('pages/catalog/catalog'),
            'catalog/:catalogGroupId(/)': require('pages/catalog/group'),
            'catalog/:catalogGroupId/:catalogCategoryId(/:catalogSubcategoryId)': require('pages/catalog/category'),

            //stores
            'stores(/)': require('pages/store/list'),
            'stores/create(/)': require('pages/store/form'),
            'stores/edit/:storeId(/)': require('pages/store/form'),
            'stores/:storeId(/)': require('pages/store/view'),

            '*path': require('pages/common/404')
        },
        initialize: function(){
            var router = this;

            router.on({
                route: function(){
                    topBar.set('active', document.location.pathname);
                }
            });
        }
    });

    return new Router();
});
