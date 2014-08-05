define(function(require) {
        //requirements
        var Model = require('kit/core/model');

        return Model.extend({
            modelName: 'department',
            urlRoot: LH.baseApiUrl + '/departments',
            saveData: [
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