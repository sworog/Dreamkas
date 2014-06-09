define(function(require) {
    //requirements

    return {
        //common
        'logout(/)': require('kit/logout/logout'),
        'signup(/)': require('pages/signup/signup'),
        'login(/)': require('pages/login/login'),
        'restorePassword(/)': require('pages/restorePassword/restorePassword'),
        '*path': require('pages/login/login')
    };
});
