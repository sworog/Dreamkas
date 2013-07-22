define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            modelName: 'department',
            urlRoot: LH.baseApiUrl + '/departments',
            saveFields: [
                'number',
                'name',
                'store'
            ],
            initialize: function(attrs, options) {

                BaseModel.prototype.initialize.apply(this, arguments);

                if (this.collection && this.collection.store) {
                    this.set('store', this.collection.store);
                }

                console.log()
            }
        });
    }
);