define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            initialize: function(options){
                this.parentClassModel = options.parentClassModel || this.collection.parentClassModel;
                this.set('klass', this.parentClassModel.id);
            },
            urlRoot: LH.baseApiUrl + '/groups',
            saveFields: [
                'name',
                'klass'
            ]
        });
    }
);