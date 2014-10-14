define(function(require) {
    //requirements
    var cookie = require('cookies');
    var location = require('kit/location/location');

    return function(token){
        cookie.set('token', token, {path: '/'});

        if (location.isContain('login')){
            location.redirect('/');
        } else {
            location.reload();
        }
    }
});