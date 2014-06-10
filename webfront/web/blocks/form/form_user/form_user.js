define(function(require) {
    //requirements
    var Form = require('kit/form');

    return Form.extend({
        el: '.form_user',
        model: require('models/currentUser.inst'),
        redirectUrl: '/users/current'
    });
});