define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        Page = require('page');

    return Form.extend({
        el: '.form_invoiceSearch',
        collections: {
            invoices: null
        },
        submit: function(formData){
            var block = this;

            Page.current.save(formData);

            return block.collections.invoices.fetch({
                data: formData
            });
        }
    });
});