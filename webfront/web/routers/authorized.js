define(function(require) {
    //requirements
    var Router = require('routers/base');

    return new Router({
        routes: {
            //common
            '(/)': require('pages/common/dashboard'),
            'logout(/)': require('utils/logout'),
            'dashboard(/)': require('pages/common/dashboard'),
            'sale(/)': require('pages/common/sale'),
            'balance(/)': require('pages/common/balance'),
            'logs(/)': require('pages/common/log'),
            'settings(/)': require('pages/common/settings'),

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
            'catalog/:catalogGroupId/:catalogCategoryId(/)(:catalogSubCategoryId)': require('pages/catalog/category'),

            //stores
            'stores(/)': require('pages/store/list'),
            'stores/create(/)': require('pages/store/form'),
            'stores/edit/:storeId(/)': require('pages/store/form'),
            'stores/:storeId(/)': require('pages/store/view'),
            'stores/:storeId/products/edit/:productId(/)': require('pages/storeProduct/from'),

            //departments
            'stores/:storeId/departments/create(/)': require('pages/department/form'),
            'stores/:storeId/departments/edit/:departmentId(/)': require('pages/department/form'),
            'stores/:storeId/departments/:departmentId(/)': require('pages/department/view'),

            //errors
            '403(/)': require('pages/errors/403'),
            '*path': require('pages/errors/404')
        }
    });
});
