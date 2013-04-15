var Invoice = BasicModel.extend({
    modelName: 'invoice',
    urlRoot: baseApiUrl + "/invoices",

    dateFormat: 'dd.mm.yy',
    datePrintFormat: "dd.mm.yyyy",
    timeFormat: 'HH:mm',
    invalidMessage: 'Вы ввели неверную дату',

    defaults: {
        id: null,
        sku: null,
        supplier: null,
        acceptanceDate: null,
        accepter: null,
        legalEntity: null,
        supplierInvoiceSku: null,
        supplierInvoiceDate: null,
        sumTotal: null
    },

    toJSON: function(options) {
        _.defaults(options || (options = {}), {
            toSave: false
        });

        var data = BasicModel.prototype.toJSON.call(this, options)

        if(options.toSave){
            data[this.modelName].sumTotal = undefined;
        }

        return data;
    }
});