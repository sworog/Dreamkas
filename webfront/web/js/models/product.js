var Product = BasicModel.extend({
    modelName: 'product',
    urlRoot: baseApiUrl + "/api/1/products",

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
        info: null
    },

    unitsEnum: {
        kg: {
            textEdit: "Килограммы",
            textView: "килограмм"
        },
        unit: {
            textEdit: "Штуки",
            textView: "штука"
        },
        liter: {
            textEdit: "Литры",
            textView: "литр"
        }
    },

    vatEnum: [0, 10, 18]
});