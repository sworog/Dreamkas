define(function(require) {
    //requirements
    var Form = require('blocks/form/form');

    var router = new Backbone.Router();

    return Form.extend({
        __name__: 'form_invoice',
        template: require('tpl!blocks/form/form_invoice/templates/index.html'),
        submitSuccess: function(model){
            router.navigate('/invoices/' + model.id + '?editMode=true', {
                trigger: true
            });
        }
    });
});