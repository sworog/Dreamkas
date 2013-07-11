define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel'),
            CatalogSubcategoriesCollection = require('collections/catalogSubcategories');

        return BaseModel.extend({
            modelName: 'catalogCategory',
            urlRoot: LH.baseApiUrl + '/categories',
            defaults: {
                parentGroupId: null
            },
            initData: {
                subCategories: CatalogSubcategoriesCollection
            },
            saveFields: [
                'name',
                'group'
            ],
            initialize: function(attrs, options) {

                BaseModel.prototype.initialize.apply(this, arguments);

                if (this.collection && this.collection.parentGroupId) {
                    this.set('parentGroupId', this.collection.parentGroupId);
                }

                this.set('group', this.get('parentGroupId'));
            },
            parse: function(response, options) {
                var data = BaseModel.prototype.parse.apply(this, arguments);

                if (typeof data.group == 'object') {
                    data.group = data.group.id;
                }

                return data;
            }
        });
    }
);