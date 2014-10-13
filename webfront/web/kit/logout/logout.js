define(function(require) {
    //requirements
    var cookie = require('cookies');
    var location = require('kit/location/location');

    return function(){
        cookie.set('token', '', {path: '/'});
        location.redirect('/');
    };
});