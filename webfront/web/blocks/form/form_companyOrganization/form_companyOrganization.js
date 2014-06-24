define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        el: '.form_companyOrganization',
        redirectUrl: '/company',
        model: function(){
            var Model = require('models/companyOrganization');

            return new Model();
        }
    });
});