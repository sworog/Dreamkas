define(function(require) {
    //requirements

    return {
        //common
        '(/)': require('pages/common/dashboard'),
        'logout(/)': require('utils/logout'),
        'dashboard(/)': require('pages/common/dashboard'),
        'sale(/)': require('pages/common/sale'),
        'balance(/)': require('pages/common/balance'),
        'logs(/)': require('pages/common/log'),
        'settings(/)': require('pages/common/settings'),

        //suppliers
        'suppliers(/)': require('pages/suppliers/list/list'),
        'suppliers/create(/)': require('pages/suppliers/create/create'),
        'suppliers/:supplierId(/)': require('pages/suppliers/view/view'),

        //orders
        'orders(/)': require('pages/orders/list/list'),
        'orders/create(/)': require('pages/orders/create/create'),

        //reports
        'reports(/)': require('pages/reports/dashboard/dashboard'),
        'reports/grossMargin': require('pages/reports/grossMargin/grossMargin'),
        'reports/grossSalesByStores': require('pages/reports/grossSalesByStores/grossSalesByStores'),
        'stores/:storeId/reports/grossSalesByHours': require('pages/reports/store/grossSalesByHours/grossSalesByHours'),
        'stores/:storeId/reports/grossSalesByGroups(/)': require('pages/reports/store/grossSalesByGroups/grossSalesByGroups'),
        'stores/:storeId/groups/:groupId/grossSalesByCategories(/)': require('pages/reports/store/grossSalesByCategories/grossSalesByCategories'),
        'stores/:storeId/groups/:groupId/categories/:categoryId/grossSalesBySubCategories(/)': require('pages/reports/store/grossSalesBySubCategories/grossSalesBySubCategories'),
        'stores/:storeId/groups/:groupId/categories/:categoryId/subCategories/:subCategoryId/grossSalesByProducts(/)': require('pages/reports/store/grossSalesByProducts/grossSalesByProducts'),
        'stores/:storeId/reports/grossMargin(/)': require('pages/reports/store/grossMargin/grossMargin'),

        //invoices
        'invoices(/)': require('pages/invoice/list'),
        'invoices/create(/)': require('pages/invoice/form'),
        'invoices/search(/)': require('pages/invoice/search'),
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
        'products/:productId/invoices': require('pages/product/invoices'),
        'products/:productId/writeoffs': require('pages/product/writeOffs'),
        'products/:productId/returns': require('pages/product/returns'),

        //writeOffs
        'writeOffs(/)': require('pages/writeOff/list'),
        'writeOffs/create(/)': require('pages/writeOff/form'),
        'writeOffs/search(/)': require('pages/writeOff/search'),
        'writeOffs/:writeOffId(/)': require('pages/writeOff/view'),

        //catalog
        'catalog(/)': require('pages/catalog/catalog'),
        'catalog/:catalogGroupId(/)': require('pages/catalog/group'),
        'catalog/:catalogGroupId/:catalogCategoryId(/)(:catalogSubCategoryId)': require('pages/catalog/category'),
        'catalog/:catalogGroupId/:catalogCategoryId/:catalogSubCategoryId(/)(:section)': require('pages/catalog/category'),

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
    };
});
