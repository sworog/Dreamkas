define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form');

    return Form.extend({
        template: require('rv!./template.html'),
        model: require('models/restorePassword'),
        data: {
            model: {
                email: null
            }
        },
        redirectUrl: '/login?restorePassword=success',
        submitSuccess: function(res){
            this.model.email = res.email;
            this._super();
        }
    });
});