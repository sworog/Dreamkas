define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form');

    return Form.extend({
        __name__: 'form_invoiceSearch',
        invoicesCollection: null,
        submit: function(formData){
            var block = this;

            return block.invoicesCollection.fetch({
                data: formData
            });
        }
    });
});