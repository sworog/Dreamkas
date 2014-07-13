define(function(require) {
    //requirements

    return {
        'logout(/)': require('kit/logout/logout'),
        'login(/)': require('pages/login/login'),
        '*path': require('pages/login/login')
    };
});
