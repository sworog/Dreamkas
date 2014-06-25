define(function(require) {
    //requirements

    return {
        //common
        '(/)': require('pages/main/main'),
        'logout(/)': require('kit/logout/logout'),
        'log(/)': require('pages/log/log'),
        'settings(/)': require('pages/settings/settings'),

        //company
        'company': require('pages/company/company'),
        'company/organizations/create': require('pages/createCompanyOrganization/createCompanyOrganization'),
        'company/organizations/:organizationId': require('pages/companyOrganization/companyOrganization'),
        'company/organizations/:organizationId/details': require('pages/companyOrganizationDetails/companyOrganizationDetails'),

        //suppliers
        'suppliers(/)': require('pages/suppliers/list/list'),
        'suppliers/create(/)': require('pages/suppliers/create/create'),
        'suppliers/:supplierId(/)': require('pages/suppliers/view/view'),

        //orders
        'stores/:storeId/orders(/)': require('pages/orders/list/list'),
        'stores/:storeId/orders/create(/)': require('pages/orders/create/create'),
        'stores/:storeId/orders/:orderId(/)': require('pages/orders/view/view'),

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
        'stores/:storeId/invoices(/)': require('pages/invoices/list/list'),
        'stores/:storeId/invoices/create(/)': require('pages/invoices/create/create'),
        'stores/:storeId/invoices/search(/)': require('pages/invoices/search/search'),
        'stores/:storeId/invoices/:invoiceId(/)': require('pages/invoices/view/view'),

        //users
        'users/current(/)': require('pages/users/current/current'),
        'users/edit(/)': require('pages/users/edit/edit'),

        //products
        'products(/)': require('pages/product/list'),
        'products/create(/)': require('pages/createProduct/createProduct'),
        'products/:productId/edit(/)': require('pages/product/form'),
        'products/:productId(/)': require('pages/products/view/view'),
        'products/:productId/invoices': require('pages/product/invoices'),
        'products/:productId/writeoffs': require('pages/product/writeOffs'),
        'products/:productId/returns': require('pages/product/returns'),
        'products/:productId/barcodes': require('pages/products/barcodes/barcodes'),

        //writeOffs
        'writeOffs(/)': require('pages/writeOff/list'),
        'writeOffs/create(/)': require('pages/writeOff/form'),
        'writeOffs/search(/)': require('pages/writeOff/search'),
        'writeOffs/:writeOffId(/)': require('pages/writeOff/view'),

        //catalog
        'catalog(/)': require('pages/catalog/catalog'),
        'groups/:groupId(/)': require('pages/group/group'),
        'groups/:groupId/categories/:categoryId(/)': require('pages/category/category'),
//        'catalog/:catalogGroupId(/)': require('pages/catalog/group'),
//        'catalog/:catalogGroupId/:catalogCategoryId(/)(:catalogSubCategoryId)': require('pages/catalog/category'),
//        'catalog/:catalogGroupId/:catalogCategoryId/:catalogSubCategoryId(/)(:section)': require('pages/catalog/category'),

        //stores
        'stores(/)': require('pages/stores/list/list'),
        'stores/create(/)': require('pages/stores/create/create'),
        'stores/:storeId/settings(/)': require('pages/stores/settings/settings'),
        'stores/:storeId(/)': require('pages/stores/dashboard/dashboard'),
        'stores/:storeId/products/:productId(/)': require('pages/stores/product/product'),
        'stores/:storeId/catalog(/)': require('pages/stores/catalog/catalog'),
        'stores/:storeId/groups/:groupId(/)': require('pages/stores/group/group'),
        'stores/:storeId/groups/:groupId/categories/:categoryId(/)': require('pages/stores/category/category'),
        'stores/:storeId/products/:productId/edit(/)': require('pages/storeProductEdit/storeProductEdit'),
        'stores/:storeId/products/:productId/barcodes(/)': require('pages/storeProductBarcodes/storeProductBarcodes'),
        'stores/:storeId/products/:productId/invoices(/)': require('pages/storeProductInvoices/storeProductInvoices'),
        'stores/:storeId/products/:productId/writeOffs(/)': require('pages/storeProductWriteOffs/storeProductWriteOffs'),
        'stores/:storeId/products/:productId/returns(/)': require('pages/storeProductReturns/storeProductReturns'),

        //departments
        'stores/:storeId/departments/create(/)': require('pages/department/form'),
        'stores/:storeId/departments/edit/:departmentId(/)': require('pages/department/form'),
        'stores/:storeId/departments/:departmentId(/)': require('pages/department/view'),

        //errors
        'errors/403(/)': require('pages/errors/403'),
        '*path': require('pages/errors/404')
    };
});
