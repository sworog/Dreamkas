define(function(require) {
    //requirements
    var Model = require('kit/model');

    return Model.extend({
        modelName: 'product',
        urlRoot: LH.baseApiUrl + '/products',
        defaults: {
            amount: 0,
            retailPricePreference: 'retailMarkup'
        },
        saveFields: [
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
            'subCategory'
        ],
        parse: function(response, options) {
            var data = Model.prototype.parse.apply(this, arguments);

            if (typeof data.subCategory == 'object') {
                data.group = data.subCategory.category.group;
                data.category = data.subCategory.category;
            }

            return data;
        }
    });
});
