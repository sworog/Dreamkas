define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form');

    return Form.extend({
        __name__: 'form_catalogGroupProperties',
        model: null,
        successMessage: 'Свойства успешно сохранены',
        templates: {
            index: require('tpl!blocks/form/form_catalogGroupProperties/templates/index.html')
        }
    });
});