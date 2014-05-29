define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form'),
        $ = require('jquery');

    return Form.extend({
        template: require('rv!./template.html'),
        redirectUrl: function(){
            var block = this;

            return '/login?email=' + block.get('email');
        },
        submit: function(){
            var block = this;

            return $.ajax({
                type: 'POST',
                data: block.data,
                url: LH.baseApiUrl + '/users/signup'
            });
        }
    });
});