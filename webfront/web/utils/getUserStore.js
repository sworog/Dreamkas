define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser');

    return function(storeId){
        if (currentUserModel.stores && currentUserModel.stores.length){
            return currentUserModel.stores.get(storeId);
        }

        return null;
    }
});