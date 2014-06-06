define(function(require) {
    //requirements
    var currentUserModel = require('models/currentUser.inst');

    return function(storeId){
        if (currentUserModel.stores.length){
            return currentUserModel.stores.get(storeId);
        }

        return null;
    }
});