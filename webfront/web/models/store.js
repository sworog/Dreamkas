define(function(require) {
        //requirements
        var Model = require('kit/model');

        return Model.extend({
            modelName: 'store',
            urlRoot: LH.baseApiUrl + '/stores',
            initData: {
                departments: require('collections/departments')
            },
            saveFields: [
                'number',
                'address',
                'contacts'
            ],
            linkManager: function(userUri) {
                return $.ajax({
                    url: '/fixtures/users.json',
                    dataType: 'json',
                    type: 'GET', // LINK
                    headers: {
                        Link: '<' + userUri + '>; rel="manager"'
                    }
                })
            },
            unlinkManager: function(userUri) {
                return $.ajax({
                    url: '/fixtures/users.json',
                    dataType: 'json',
                    type: 'GET', // UNLINK
                    headers: {
                        Unlink: '<' + userUri + '>; rel="manager"'
                    }
                })
            }
        });
    }
);