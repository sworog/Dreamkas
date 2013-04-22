define(
    [
        './main.js'
    ],
    function(BaseModel) {
    return BaseModel.extend({
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
            amount: 0,
            lastPurchasePrice: null
        },

        excludeSaveFields: [
            'amount',
            'lastPurchasePrice'
        ],

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
});