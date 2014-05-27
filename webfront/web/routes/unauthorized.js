define(function(require) {
    //requirements

    return {
        //common
        'signup(/)': require('pages/signup/signup'),
        '*path': require('pages/common/login')
    };
});
