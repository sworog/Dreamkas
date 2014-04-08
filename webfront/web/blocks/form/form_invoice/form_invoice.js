define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        router = require('router')

    return Form.extend({
        __name__: 'form_invoice',
        template: require('tpl!blocks/form/form_invoice/templates/index.html'),
        submitSuccess: function(){
            var block = this;

            router.navigate('/stores/' + block.model.get('store.id') + '/invoices/' + block.model.id + '?editMode=true');
        }
    });
});