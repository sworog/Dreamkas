define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            urlRoot: LH.baseApiUrl + '/categories',
            defaults: {
                subcategories: []
            },
            saveFields: [
                'name',
                'group'
            ],
            initialize: function(options){
                this.parentGroupModel = options.parentGroupModel || this.collection.parentGroupModel;
                this.set('group', this.parentGroupModel.id);
            }
        });
    }
);