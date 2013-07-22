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
            initialize: function(attrs, options) {

                Model.prototype.initialize.apply(this, arguments);

                if (this.collection && this.collection.store) {
                    this.set('store', this.collection.store);
                }

                console.log()
            }
        });
    }
);