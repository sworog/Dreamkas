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
        redirectUrl: function() {
            var block = this;

            return '/login?email=' + block.get('model.email');
        }
    });
});