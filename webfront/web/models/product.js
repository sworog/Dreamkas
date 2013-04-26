define(
    [
        './baseModel.js'
    ],
    function(BaseModel) {
        return BaseModel.extend({
            modelName: "product",
            url: function() {
                var url;
                if (this.id){
                    url = baseApiUrl + '/products/' + this.id + '.json';
                } else {
                    url = baseApiUrl + '/products.json'
                }
                return url;
            },
            defaults: {
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
