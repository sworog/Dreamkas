define(function(require) {
    //requirements

    require('madmin/js/jquery-ui');
    require('madmin/vendors/bootstrap/js/bootstrap.min');
    require('madmin/vendors/bootstrap-hover-dropdown/bootstrap-hover-dropdown');

    return {
        //common
        '(/)': require('pages/main/main'),
        'logout(/)': require('kit/logout/logout')
    };
});
