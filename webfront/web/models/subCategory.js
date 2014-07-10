define(function(require) {
        //requirements
        var Model = require('kit/model/model'),
            ProductsCollection = require('collections/catalogProducts');

        return Model.extend({
            defaults: {
                categoryId: null,
                groupId: null
            },
            urlRoot: Model.baseApiUrl + '/subcategories',
            saveData: function() {
                return {
                    name: this.get('name'),
                    category: this.get('categoryId'),
                    retailMarkupMax: this.get('retailMarkupMax'),
                    retailMarkupMin: this.get('retailMarkupMin'),
                    rounding: this.get('rounding.name')
                }
            },
            initialize: function() {
                this.collections = {
                    products: new ProductsCollection(this.get('products'), {
                        subCategoryId: this.id
                    })
                }
            },
            parse: function(response, options) {
                var data = Model.prototype.parse.apply(this, arguments);

                if (this.collections){
                    this.collections.products.reset(data.products);
                }

                return data;
            }
        });
    }
);