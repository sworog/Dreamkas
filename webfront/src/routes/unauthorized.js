define(function(require) {
    //requirements

    return {
        'logout(/)': require('kit/logout/logout'),
        'login(/)': require('pages/login/login'),
        'signup(/)': require('pages/signup/signup'),
        '*path': require('pages/login/login')
    };
});
