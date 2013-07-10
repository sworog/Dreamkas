define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
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

                if (this.collection && this.collection.parentGroupModel){
                    this.parentGroupModel = this.collection.parentGroupModel;
                }

                if (options && options.parentGroupModel){
                    this.parentGroupModel = options.parentGroupModel;
                }

                this.set('group', this.parentGroupModel.id);
            }
        });
    }
);