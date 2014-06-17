define(function(require) {
    //requirements
    var Form = require('kit/form');

    return Form.extend({
        el: '.form_groupProperties',
        model: null,
        successMessage: 'Свойства успешно сохранены'
    });
});