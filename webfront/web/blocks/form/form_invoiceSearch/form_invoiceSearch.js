define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        invoiceList_search = require('tpl!blocks/invoiceList/invoiceList_search.html');

    return Form.extend({
        __name__: 'form_invoiceSearch',
        invoicesCollection: null,
        listeners: {
            invoicesCollection: {
                reset: function(){
                    var block = this;

                    block.$results.html(invoiceList_search({
                        invoicesCollection: block.invoicesCollection,
                        searchQuery: block.formData
                    }));
                }
            }
        },
        submit: function(formData){
            var block = this;

            return block.invoicesCollection.fetch({
                data: formData
            });
        }
    });
});