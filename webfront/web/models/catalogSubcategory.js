define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            modelName: 'catalogSubcategory',
            urlRoot: LH.baseApiUrl + '/subcategories',
            parentGroupModel: {},
            defaults: {
                subcategories: []
            },
            saveFields: [
                'name',
                'category'
            ],
            initialize: function(attrs, options){

                BaseModel.prototype.initialize.apply(this, arguments);

                if (this.collection && this.collection.parentCategoryModel){
                    this.parentCategoryModel = this.collection.parentCategoryModel;
                }

                if (options && options.parentCategoryModel){
                    this.parentCategoryModel = options.parentCategoryModel;
                }

                this.set('category', this.parentCategoryModel.id);
            }
        });
    }
);