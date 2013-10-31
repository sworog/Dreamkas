define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        writeOffList_search = require('tpl!blocks/writeOffList/writeOffList_search.html'),
        InvoiceProductsCollection = require('collections/invoiceProducts'),
        Table_invoiceProducts = require('blocks/table/table_invoiceProducts/table_invoiceProducts');

    return Form.extend({
        __name__: 'form_invoiceSearch',
        writeOffsCollection: null,
        listeners: {
            writeOffsCollection: {
                reset: function(){
                    var block = this;

//                    block.$results.html(writeOffList_search({
//                        writeOffsCollection: block.writeOffsCollection,
//                        searchQuery: block.formData
//                    }));
                }
            }
        },
        submit: function(formData){
            var block = this;

            return block.writeOffsCollection.fetch({
                data: formData
            });
        }
    });
});