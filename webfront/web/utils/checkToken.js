define(function(require) {
    //requirements
    var $ = require('jquery'),
        currentUserModel = require('models/currentUser');

    require('jquery.cookie');

    var deferred = $.Deferred(),
        token = $.cookie('token');

    return function() {
        if (!token) {
            deferred.resolve({
                status: 'nonAuthorized',
                message: 'no token'
            });
        } else {
            currentUserModel.fetch({
                success: function(model) {
                    deferred.resolve({
                        status: 'authorized'
                    });
                },
                error: function(data) {
                    console.log(data);
                }
            });
        }

        return deferred.promise();
    }

});