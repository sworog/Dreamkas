define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form');

    require('backbone');

    return Form.extend({
        model: Backbone.Model.extend({
            url: LH.baseApiUrl + '/users/signup'
        }),
        template: require('rv!./template.html'),
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