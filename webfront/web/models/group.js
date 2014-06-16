define(function(require) {
        //requirements
        var Model = require('kit/model'),
            CategoriesCollection = require('collections/categories');

        return Model.extend({
            defaults: {
                storeId: null
            },
            initialize: function() {
                this.collections = {
                    categories: new CategoriesCollection(this.get('categories'), {
                        group: this
                    })
                }
            },
            urlRoot: function() {
                if (this.get('storeId')) {
                    return Model.baseApiUrl + '/stores/' + this.get('storeId') + '/groups';
                } else {
                    return Model.baseApiUrl + '/groups';
                }
            },
            saveData: function() {
                return {
                    name: this.get('name'),
                    retailMarkupMax: this.get('retailMarkupMax'),
                    retailMarkupMin: this.get('retailMarkupMin'),
                    rounding: this.get('rounding.name')
                }
            },
            parse: function(response, options) {
                var data = Model.prototype.parse.apply(this, arguments);

                if (this.collections){
                    this.collections.categories.reset(data.categories);
                }

                return data;
            }
        });
    }
);