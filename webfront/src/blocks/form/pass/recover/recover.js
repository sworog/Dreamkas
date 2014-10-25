define(function(require, exports, module) {
    //requirements
    var Form_pass = require('blocks/form/pass/pass');

    return Form_pass.extend({
        model: require('resources/recover/model'),
        redirectUrl: '/login?recover=success',
        title: 'Восстановление пароля',
        submitButtonCaption: 'Восстановить'
    });
});