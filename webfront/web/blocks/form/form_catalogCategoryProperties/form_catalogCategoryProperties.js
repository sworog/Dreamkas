define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form');

    return Form.extend({
        __name__: 'form_catalogCategoryProperties',
        model: null,
        successMessage: 'Свойства успешно сохранены',
        template: require('tpl!blocks/form/form_catalogCategoryProperties/templates/index.html'),
    });
});