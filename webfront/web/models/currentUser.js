define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    var permissions = {
        ROLE_ADMINISTRATOR: {
            users: 'all'
        },
        ROLE_COMMERCIAL_MANAGER: {
            klasses: 'all',
            products: 'all',
            balance: 'all'
        },
        ROLE_STORE_MANAGER: {
        },
        ROLE_DEPARTMENT_MANAGER: {
            invoices: 'all',
            writeoffs: 'all',
            balance: 'all',
            products: 'get'
        }
    };

    var CurrentUserModel = BaseModel.extend({
        urlRoot: LH.baseApiUrl + '/users/current'
    });

    var currentUserModel = new CurrentUserModel();

    currentUserModel.on('change:role', function(model, role, options){
        model.set('permissions', permissions[role]);
    });

    return currentUserModel;
});