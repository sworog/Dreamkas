define(function(require) {
        //requirements
        var Model = require('kit/model');

        return Model.extend({
            modelName: 'catalogGroup',
            defaults: {
                rounding: 'nearest1'
            },
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
            saveFields: [
                'name',
                'retailMarkupMax',
                'retailMarkupMin',
                'rounding'
            ]
        });
    }
);