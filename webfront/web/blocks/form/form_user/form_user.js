define(function(require) {
    //requirements
    var Form = require('kit/form');

    return Form.extend({
        el: '.form_user',
        redirectUrl: '/users/current'
    });
});