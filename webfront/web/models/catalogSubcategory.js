define(function(require) {
        //requirements
        var BaseModel = require('models/baseModel');

        return BaseModel.extend({
            modelName: 'catalogSubcategory',
            urlRoot: LH.baseApiUrl + '/subcategories',
            saveFields: [
                'name',
                'category'
            ],
            initialize: function(attrs, options) {

                BaseModel.prototype.initialize.apply(this, arguments);

                if (this.collection && this.collection.category) {
                    this.set('category', this.collection.category);
                }

                if (this.collection && this.collection.group) {
                    this.set('group', this.collection.group);
                }
            },
            parse: function(response, options) {
                var data = BaseModel.prototype.parse.apply(this, arguments);

                if (typeof data.category == 'object') {
                    data.group = data.category.group.id;
                    data.category = data.category.id;
                }

                return data;
            }
        });
    }
);