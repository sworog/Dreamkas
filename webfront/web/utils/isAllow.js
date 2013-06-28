define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser'),
        _ = require('underscore');

    return function(resource, method){

        method = method || 'get';

        var isAllow = false,
            permissions = currentUserModel.get('permissions')[resource];

        if (permissions && permissions === 'all'){
            isAllow = true;
        } else if(typeof permissions === 'string') {
            isAllow = permissions === method;
        } else if(permissions){
            isAllow = _.indexOf(permissions, method) >= 0;
        }

        return isAllow;
    }
});