define(function(require) {
    //requirements

    return {
        //common
        '(/)': require('pages/main/main'),
        'logout(/)': require('kit/logout/logout'),

		//stockMovement
		'stockMovements(/)': require('pages/stockMovements/stockMovements'),

		//reports
		'reports(/)': require('pages/reports/reports'),
		'reports/stockBalance(/)': require('pages/reports/stockBalance/stockBalance'),

        //catalog
        'catalog(/)': require('pages/catalog/catalog'),
        'catalog/groups/:groupId(/)': require('pages/catalog/groups/group/group'),

        //suppliers
        'suppliers(/)': require('pages/suppliers/suppliers'),

        //stores
        'stores(/)': require('pages/stores/stores'),

        //pos
        'pos(/)': require('pages/pos/pos'),
        'pos/stores/:storeId(/)': require('pages/pos/store/store'),
		'pos/stores/:storeId/sales(/)': require('pages/pos/sales/sales'),

        //404
        '*path': require('pages/404/404')
    };
});
