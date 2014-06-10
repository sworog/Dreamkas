define(function(require) {
        //requirements
        var Model = require('kit/model');

        return Model.extend({
            urlRoot: Model.baseApiUrl + '/subcategories',
            saveData: function(){
                return {
                    name: this.get('name'),
                    category: this.get('category'),
                    retailMarkupMax: this.get('retailMarkupMax'),
                    retailMarkupMin: this.get('retailMarkupMin'),
                    rounding: this.get('rounding.name')
                }
            },
            categoryId: null,
            groupId: null
        });
    }
);