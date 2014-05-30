define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form'),
        signupModel = require('models/signup.inst');

    return Form.extend({
        template: require('rv!./template.html'),
        data: {
            model: {
                email: null
            }
        },
        submit: function(){
            return signupModel.save(this.get('model'));
        },
        redirectUrl: '/login?signup=success'
    });
});