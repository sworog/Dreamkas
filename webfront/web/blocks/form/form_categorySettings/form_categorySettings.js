define(function(require) {
    //requirements
    var Form = require('kit/form');

    return Form.extend({
        el: '.form_categorySettings',
        model: null,
        successMessage: 'Свойства успешно сохранены'
    });
});