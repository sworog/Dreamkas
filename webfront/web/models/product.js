define(
    [
        './baseModel.js'
    ],
    function(BaseModel) {
        return BaseModel.extend({
            modelName: 'product',
            urlRoot: baseApiUrl + '/products',
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
