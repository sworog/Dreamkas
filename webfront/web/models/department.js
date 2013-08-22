define(function(require) {
        //requirements
        var Model = require('kit/model');

        return Model.extend({
            modelName: 'department',
            urlRoot: LH.baseApiUrl + '/departments',
            saveFields: [
                'number',
                'name',
                'store'
            ],
            initialize: function() {
                if (this.collection && this.collection.store) {
                    this.set('store', this.collection.store);
                }
            }
        });
    }
);