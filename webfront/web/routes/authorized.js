define(function(require) {
    //requirements

    return {
        //common
        '(/)': require('pages/main/main'),
        'logout(/)': require('kit/logout/logout')
    };
});
