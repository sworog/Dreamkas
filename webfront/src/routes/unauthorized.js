define(function(require) {
    //requirements

    return {
        'logout(/)': require('kit/logout/logout'),
        'login(/)': require('pages/login/login'),
        'signup(/)': require('pages/signup/signup'),
        'recover(/)': require('pages/recover/recover'),
        '*path': require('pages/login/login')
    };
});
