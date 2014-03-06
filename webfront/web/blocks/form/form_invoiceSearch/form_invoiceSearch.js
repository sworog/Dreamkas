define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        invoiceList_search = require('tpl!blocks/invoiceList/invoiceList_search.html'),
        InvoiceProductsCollection = require('collections/invoiceProducts'),
        Table_invoiceProducts = require('blocks/table/table_invoiceProducts/table_invoiceProducts');

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

                    block.invoicesCollection.forEach(function(invoiceModel, index){
                        new Table_invoiceProducts({
                            collection: new InvoiceProductsCollection(invoiceModel.get('products') || [], {
                                invoiceId: invoiceModel.id,
                                storeId: invoiceModel.get('store.id')
                            }),
                            el: block.el.getElementsByClassName('invoice__productsTable')[index]
                        });
                    });
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