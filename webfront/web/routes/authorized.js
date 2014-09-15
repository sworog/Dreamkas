define(function(require) {
    //requirements

    return {
        //common
        '(/)': require('pages/main/main'),
        'logout(/)': require('kit/logout/logout'),

        //catalog
        'catalog(/)': require('pages/catalog/catalog'),
        'catalog/groups/:groupId(/)': require('pages/catalog/groups/group/group'),

        //suppliers
        'suppliers(/)': require('pages/suppliers/suppliers'),

        //stockMovement
        'stockMovements(/)': require('pages/stockMovements/stockMovements'),

        //stores
        'stores(/)': require('pages/stores/stores'),

        //pos
        'pos(/)': require('pages/pos/pos'),
        'pos/store/:storeId(/)': require('pages/pos/part/store/store'),
		'pos/store/:storeId/sales(/)': require('pages/pos/part/refund/refund'),

        //404
        '*path': require('pages/404/404')
    };
});
