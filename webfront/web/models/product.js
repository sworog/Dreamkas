define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    return BaseModel.extend({
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
            'retailPrice',
            'retailMarkup',
            'retailPricePreference',
            'barcode',
            'sku',
            'vendorCountry',
            'vendor',
            'info',
            'subCategory'
        ],
        parse: function(response, options) {
            var data = BaseModel.prototype.parse.apply(this, arguments);

            if (typeof data.subCategory == 'object') {
                data.group = data.subCategory.category.group;
                data.category = data.subCategory.category;
            }

            return data;
        }
    });
});
