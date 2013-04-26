define(
    [
        './baseModel.js'
    ],
    function(baseModel) {
        return baseModel.extend({
            modelName: 'invoiceProduct',
            url: function() {
                var url = baseApiUrl + '/invoices/' + this.get('invoice').id;

                if (this.id) {
                    url += '/products/' + this.id + '.json';
                } else {
                    url += '/products.json';
                }

                return url;
            },
            saveFields: [
                'product',
                'quantity',
                'price'
            ]
        });
    });