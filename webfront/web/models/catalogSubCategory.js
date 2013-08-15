define(function(require) {
        //requirements
        var Model = require('kit/model');

        return Model.extend({
            modelName: 'catalogSubCategory',
            urlRoot: LH.baseApiUrl + '/subcategories',
            saveFields: [
                'name',
                'category',
                'retailMarkupMax',
                'retailMarkupMin',
                'rounding'
            ],
            initialize: function(attrs, options) {

                Model.prototype.initialize.apply(this, arguments);

                if (this.collection && this.collection.category) {
                    this.set('category', this.collection.category);
                }

                if (this.collection && this.collection.group) {
                    this.set('group', this.collection.group);
                }
            },
            parse: function(response, options) {

                var data = Model.prototype.parse.apply(this, arguments);

                if (!options.parse){
                    return data;
                }

                if (typeof data.category == 'object') {
                    data.group = data.category.group.id;
                    data.category = data.category.id;
                }

                return data;
            }
        });
    }
);