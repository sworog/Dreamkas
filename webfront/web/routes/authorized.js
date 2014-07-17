define(function(require) {
    //requirements

    require('madmin/js/jquery-ui');
    require('madmin/vendors/bootstrap/js/bootstrap');
    require('madmin/vendors/bootstrap-hover-dropdown/bootstrap-hover-dropdown');

    return {
        //common
        '(/)': require('pages/main/main'),
        'logout(/)': require('kit/logout/logout'),

        //catalog
        'catalog(/)': require('pages/catalog/catalog'),
        'catalog/groups/:groupId(/)': require('pages/catalog/catalog')
    };
});
