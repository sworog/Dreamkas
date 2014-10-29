define(function(require, exports, module) {
    //requirements
    var mockjax = require('kit/mockjax/mockjax'),
        config = require('config');

    mockjax({
        url: config.baseApiUrl + '/suppliers',
        status: 200,

        responseText: [{
            "id": "544e281e2cde6ea7798b4588",
            "name": "Первый",
            "departments": [],
            "storeManagers": [{
                "id": "544e27f72cde6ed3038b4567",
                "email": "owner@lighthouse.pro",
                "name": "owner@lighthouse.pro",
                "position": "ROLE_COMMERCIAL_MANAGER",
                "roles": ["ROLE_COMMERCIAL_MANAGER", "ROLE_STORE_MANAGER", "ROLE_DEPARTMENT_MANAGER", "ROLE_ADMINISTRATOR"],
                "project": {"id": "544e27f72cde6ed3038b4568"}
            }],
            "departmentManagers": [{
                "id": "544e27f72cde6ed3038b4567",
                "email": "owner@lighthouse.pro",
                "name": "owner@lighthouse.pro",
                "position": "ROLE_COMMERCIAL_MANAGER",
                "roles": ["ROLE_COMMERCIAL_MANAGER", "ROLE_STORE_MANAGER", "ROLE_DEPARTMENT_MANAGER", "ROLE_ADMINISTRATOR"],
                "project": {"id": "544e27f72cde6ed3038b4568"}
            }]
        }]

    });
});