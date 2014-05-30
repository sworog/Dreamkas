define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form');

    return Form.extend({
        template: require('rv!./template.html'),
        model: require('models/signup'),
        data: {
            model: {
                email: null
            }
        },
        redirectUrl: '/login?signup=success',
        submitSuccess: function(res){
            this.model.email = res.email;
            this._super();
        }
    });
});