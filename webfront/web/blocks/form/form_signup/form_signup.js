define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form');

    return Form.extend({
        el: '.form_signup',
        model: require('models/signup'),
        redirectUrl: '/login?signup=success',
        submitSuccess: function(res){
            var block = this;

            block.model.email = res.email;

            Form.prototype.submitSuccess.apply(block, arguments);
        }
    });
});