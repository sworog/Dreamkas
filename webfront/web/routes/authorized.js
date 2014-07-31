define(function(require) {
    //requirements

    return {
        //common
        '(/)': require('pages/main/main'),
        'logout(/)': require('kit/logout/logout'),

        //catalog
        'catalog(/)': require('pages/catalog/catalog'),
        'catalog/groups/:groupId(/)': require('pages/group/group'),

        //stockMovement
        'stockMovement(/)': require('pages/stockMovement/stockMovement')
    };
});
