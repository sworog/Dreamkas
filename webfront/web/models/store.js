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
            linkManager: function(userUrl) {
                return $.ajax({
                    url: this.url(),
                    dataType: 'json',
                    type: 'LINK',
                    headers: {
                        Link: '<' + userUrl + '>; rel="manager"'
                    }
                })
            },
            unlinkManager: function(userUrl) {
                return $.ajax({
                    url: this.url(),
                    dataType: 'json',
                    type: 'UNLINK',
                    headers: {
                        Unlink: '<' + userUrl + '>; rel="manager"'
                    }
                })
            }
        });
    }
);