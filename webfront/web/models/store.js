define(function(require) {
        //requirements
        var Model = require('kit/core/model'),
            cookie = require('cookies');

        return Model.extend({
            modelName: 'store',
            urlRoot: LH.baseApiUrl + '/stores',
            nestedData: {
                departments: require('collections/departments'),
                storeManagers: require('collections/storeManagers'),
                departmentManagers: require('collections/departmentManagers')
            },
            saveData: [
                'number',
                'address',
                'contacts'
            ],
            linkManager: function(userUrl, type) {
                return $.ajax({
                    url: this.url(),
                    dataType: 'json',
                    type: 'POST',
                    headers: {
                        Link: '<' + userUrl + '>; rel="' + type + 'Managers"',
                        Authorization: 'Bearer ' + cookie.get('token')
                    },
                    data: {
                        _method: 'LINK'
                    }
                })
            },
            unlinkManager: function(userUrl, type) {
                return $.ajax({
                    url: this.url(),
                    dataType: 'json',
                    type: 'POST',
                    headers: {
                        Link: '<' + userUrl + '>; rel="' + type + 'Managers"',
                        Authorization: 'Bearer ' + cookie.get('token')
                    },
                    data: {
                        _method: 'UNLINK'
                    }
                })
            },
            linkStoreManager: function(userUrl) {
                return this.linkManager(userUrl, "store");
            },
            unlinkStoreManager: function(userUrl) {
                return this.unlinkManager(userUrl, "store");
            },
            linkDepartmentManager: function(userUrl) {
                return this.linkManager(userUrl, "department");
            },
            unlinkDepartmentManager: function(userUrl) {
                return this.unlinkManager(userUrl, "department");
            }
        });
    }
);