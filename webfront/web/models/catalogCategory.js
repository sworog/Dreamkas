define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            modelName: 'catalogCategory',
            urlRoot: LH.baseApiUrl + '/categories',
            parentGroupModel: {},
            defaults: {
                subcategories: []
            },
            saveFields: [
                'name',
                'group'
            ],
            initialize: function(attrs, options){

                BaseModel.prototype.initialize.apply(this, arguments);

                if (!this.get('group')){
                    if (this.collection && this.collection.parentGroupModel){
                        this.set('group', this.collection.parentGroupModel.id);
                    }
                }
            }
        });
    }
);