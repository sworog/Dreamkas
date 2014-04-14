define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        invoiceList_search = require('tpl!blocks/invoiceList/invoiceList_search.html');

    return Form.extend({
        collections: {
            invoices: null
        },
        submit: function(formData){
            var block = this;

            return block.invoicesCollection.fetch({
                data: formData
            });
        }
    });
});