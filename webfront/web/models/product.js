define(function(require) {
    //requirements
    var Model = require('kit/core/model');

    require('models/roundings');

    return Model.extend({
        modelName: 'product',
        urlRoot: LH.baseApiUrl + '/products',
        defaults: {
            amount: 0,
            retailPricePreference: 'retailMarkup'
        },
        saveData: [
            'name',
            'units',
            'vat',
            'purchasePrice',
            'retailPriceMin',
            'retailPriceMax',
            'retailMarkupMax',
            'retailMarkupMin',
            'retailPricePreference',
            'barcode',
            'sku',
            'vendorCountry',
            'vendor',
            'info',
            'subCategory',
            'rounding'
        ],
        parse: function(response, options) {
            var data = Model.prototype.parse.apply(this, arguments);

            if (data.product){
                data = data.product;
            }

            if (typeof data.subCategory == 'object') {
                data.group = data.subCategory.category.group;
                data.category = data.subCategory.category;
            }

            if (typeof data.rounding == 'object') {
                data.rounding = data.rounding.name;
            }

            return data;
        }
    });
});
