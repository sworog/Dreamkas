define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser');

    return function(resource, method){

        method = method || 'GET';

        var isAllow = false,
            permissions = currentUserModel.permissions.get(resource);

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