var Product = BasicModel.extend({
    modelName: 'product',
    urlRoot: baseApiUrl + "/products",

    defaults: {
        id: null,
        name: null,
        units: null,
        vat: null,
        purchasePrice: null,
        barcode: null,
        sku: null,
        vendorCountry: null,
        vendor: null,
        info: null,
        amount: null
    },

    toJSON: function(options) {
        _.defaults(options || (options = {}), {
            toSave: false
        });

        var data = BasicModel.prototype.toJSON.call(this, options)

        if(options.toSave){
            data[this.modelName].amount = undefined;
        }

        return data;
    },

    unitsEnum: {
        kg: {
            textEdit: "Килограммы",
            textView: "килограмм",
            textViewShort: "кг"
        },
        unit: {
            textEdit: "Штуки",
            textView: "штука",
            textViewShort: "шт."
        },
        liter: {
            textEdit: "Литры",
            textView: "литр",
            textViewShort: "л"
        }
    },

    vatEnum: [0, 10, 18]
});