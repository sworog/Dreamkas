define(function(require) {
        //requirements
        var Model = require('kit/model'),
            SubCategoriesCollections = require('collections/subCategories');

        return Model.extend({
            urlRoot: Model.baseApiUrl + '/categories',
            collections: {
                subCategories: require('collections/subCategories')
            },
            saveData: function(){
                return {
                    name: this.get('name'),
                    group: this.get('group'),
                    retailMarkupMax: this.get('retailMarkupMax'),
                    retailMarkupMin: this.get('retailMarkupMin'),
                    rounding: this.get('rounding.name')
                }
            },
            initialize: function() {
                this.collections.subCategories = new SubCategoriesCollections();
            },
            parse: function(response, options) {
                var data = Model.prototype.parse.apply(this, arguments);

                this.collections.subCategories.reset(data.subCategories);

                return data;
            }
        });
    }
);