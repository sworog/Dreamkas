define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form');

    return Form.extend({
        __name__: 'form_catalogGroupProperties',
        model: null,
        templates: {
            index: require('tpl!blocks/form/form_catalogGroupProperties/templates/index.html')
        },
        onSubmitSuccess: function(){
            var block = this;

            Form.prototype.onSubmitSuccess.apply(block, arguments);
        }
    });
});