define(function(require) {
    //requirements
    var $ = require('jquery'),
        currentUserModel = require('models/currentUser');

    require('jquery.cookie');

    var deferred = $.Deferred(),
        token = $.cookie('token');

    return function() {
        if (!token) {
            deferred.reject({
                status: 'unauthorized',
                message: 'no token'
            });
        } else {
            deferred.resolve({
                status: 'authorized'
            });
        }

        return deferred.promise();
    }

});