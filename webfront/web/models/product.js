define(function(require) {
    //requirements
    var BaseModel = require('models/baseModel');

    return BaseModel.extend({
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
                'info'
            ]
        });
    });
