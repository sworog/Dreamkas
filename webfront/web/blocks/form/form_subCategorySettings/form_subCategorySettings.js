define(function(require) {
    //requirements
    var Form = require('kit/form');

    return Form.extend({
        el: '.form_subCategorySettings',
        model: null,
        successMessage: 'Свойства успешно сохранены'
    });
});