define(function(require) {
        //requirements
        var Model = require('kit/model'),
            SubCategoriesCollections = require('collections/subCategories');

        return Model.extend({
            urlRoot: Model.baseApiUrl + '/categories',
            saveData: function() {
                return {
                    name: this.get('name'),
                    group: this.get('groupId'),
                    retailMarkupMax: this.get('retailMarkupMax'),
                    retailMarkupMin: this.get('retailMarkupMin'),
                    rounding: this.get('rounding.name')
                }
            },
            initialize: function() {

                if (!this.get('groupId')) {
                    this.set('groupId', this.collection.groupId)
                }

                this.collections = {
                    subCategories: new SubCategoriesCollections(this.get('subCategories'))
                };
            },
            parse: function() {
                var data = Model.prototype.parse.apply(this, arguments);

                if (this.collections){
                    this.collections.subCategories.reset(data.subCategories);
                }

                return data;
            }
        });
    }
);