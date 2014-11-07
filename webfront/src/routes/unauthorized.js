define(function(require) {
    //requirements

    return {
        'logout(/)': require('kit/logout/logout'),
        'login(/)': require('pages/outside/login/login'),
        'signup(/)': require('pages/outside/signup/signup'),
        'recover(/)': require('pages/outside/recover/recover'),
        '*path': require('pages/outside/login/login')
    };
});
