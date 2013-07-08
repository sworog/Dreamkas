define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            initialize: function(options){
                this.parentGroupModel = options.parentGroupModel || this.collection.parentGroupModel;
                this.set('group', this.parentGroupModel.id);
            },
            urlRoot: LH.baseApiUrl + '/groups',
            saveFields: [
                'name',
                'group'
            ]
        });
    }
);