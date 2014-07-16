define(function(require) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        el: '.form_user',
        model: require('models/currentUser/currentUser.inst'),
        redirectUrl: '/users/current'
    });
});