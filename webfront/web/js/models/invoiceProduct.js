var InvoiceProduct = BasicModel.extend({
    modelName: "invoiceProduct",

    defaults: {
        id: null,
        product: null,
        amount: null,
        price: null
    }
})