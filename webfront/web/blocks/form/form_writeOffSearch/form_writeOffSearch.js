define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        writeOffList_search = require('tpl!blocks/writeOffList/writeOffList_search.html'),
        WriteOffProductsCollection = require('collections/writeOffProducts'),
        Table_writeOffProducts = require('blocks/table/table_writeOffProducts/table_writeOffProducts');

    return Form.extend({
        __name__: 'form_invoiceSearch',
        writeOffsCollection: null,
        listeners: {
            writeOffsCollection: {
                reset: function(){
                    var block = this;

                    block.$results.html(writeOffList_search({
                        writeOffsCollection: block.writeOffsCollection,
                        searchQuery: block.formData
                    }));

                    block.writeOffsCollection.forEach(function(writeOffModel, index){
                        console.log(block.el.getElementsByClassName('writeOff__productsTable'));
                        new Table_writeOffProducts({
                            collection: new WriteOffProductsCollection(writeOffModel.get('products') || [], {
                                writeOffId: writeOffModel.id,
                                storeId: writeOffModel.get('store.id')
                            }),
                            el: block.el.getElementsByClassName('writeOff__productsTable')[index]
                        });
                    });
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