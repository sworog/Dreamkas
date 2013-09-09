define(function(require) {
        //requirements
        var Model = require('kit/model');

        return Model.extend({
            modelName: 'catalogSubCategory',
            urlRoot: LH.baseApiUrl + '/subcategories',
            saveFields: function(){
                return {
                    name: this.get('name'),
                    category: this.get('category'),
                    retailMarkupMax: this.get('retailMarkupMax'),
                    retailMarkupMin: this.get('retailMarkupMin'),
                    rounding: this.get('rounding') ? this.get('rounding').name : null
                }
            },
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

                if (typeof data.rounding == 'object') {
                    data.rounding = data.rounding.name;
                }

                return data;
            }
        });
    }
);