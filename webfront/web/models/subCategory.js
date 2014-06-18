define(function(require) {
        //requirements
        var Model = require('kit/model');

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
            }
        });
    }
);