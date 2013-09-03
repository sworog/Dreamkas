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
            initData: {
                categories: require('collections/catalogCategories')
            },
            saveFields: function(){
                return {
                    name: this.get('name'),
                    retailMarkupMax: this.get('retailMarkupMax'),
                    retailMarkupMin: this.get('retailMarkupMin'),
                    rounding: this.get('rounding') ? this.get('rounding').name : 'nearest1'
                }
            },
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