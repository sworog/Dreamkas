define(function(require, exports, module) {
    //requirements
    var Form_pass = require('blocks/form/pass/pass');

    return Form_pass.extend({
        model: require('resources/signup/model'),
        redirectUrl: '/login?signup=success',
        title: 'Регистрация',
        submitButtonCaption: 'Зарегистрироваться'
    });
});