define(function(require) {
    //requirements

    return {
        //common
        'signup(/)': require('pages/signup/signup'),
        'login(/)': require('pages/login/login'),
        '*path': require('pages/login/login')
    };
});
