define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    var permissions = {
        administrator: {
            users: 'all'
        },
        commercialManager: {
            klasses: 'all',
            groups: 'all',
            products: 'all'
        },
        storeManager: {
            balance: 'all'
        },
        departmentManager: {
            invoices: 'all',
            writeoffs: 'all'
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