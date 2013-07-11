define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            modelName: 'catalogSubcategory',
            urlRoot: LH.baseApiUrl + '/subcategories',
            defaults: {
                parentCategoryId: null,
                parentGroupId: null
            },
            saveFields: [
                'name',
                'category'
            ],
            initialize: function(attrs, options) {

                BaseModel.prototype.initialize.apply(this, arguments);

                if (this.collection && this.collection.parentCategoryId) {
                    this.set('parentCategoryId', this.collection.parentCategoryId);
                }

                if (this.collection && this.collection.parentGroupId) {
                    this.set('parentGroupId', this.collection.parentGroupId);
                }

                this.set('category', this.get('parentCategoryId'));
            }
        });
    }
);