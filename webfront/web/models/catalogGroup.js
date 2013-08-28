define(function(require) {
        //requirements
        var Model = require('kit/model');

        return Model.extend({
            modelName: 'catalogGroup',
            urlRoot: function(){
                if (this.get('storeId')){
                    return LH.baseApiUrl + '/stores/' + this.get('storeId') + '/groups';
                } else {
                    return LH.baseApiUrl + '/groups';
                }
            },
            nestedData: {
                categories: require('collections/catalogCategories')
            },
            saveData: [
                'name',
                'retailMarkupMax',
                'retailMarkupMin',
                'rounding'
            ],
            parse: function(response, options) {
                var data = Model.prototype.parse.apply(this, arguments);

                if (typeof data.rounding == 'object') {
                    data.rounding = data.rounding.name;
                }

                return data;
            }
        });
    }
);