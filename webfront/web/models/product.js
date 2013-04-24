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
                name: null,
                units: null,
                vat: null,
                purchasePrice: null,
                retailPrice: null,
                retailMarkup: null,
                retailPricePreference: 'retailMarkup',
                barcode: null,
                sku: null,
                vendorCountry: null,
                vendor: null,
                info: null
            }
        });
    });
