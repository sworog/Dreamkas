define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        el: '.form_companyOrganization',
        redirectUrl: function(){
            var page = this;

            if (!page.get('model.id')){
                return '/company';
            }
        },
        successMessage: 'Данные организации успешно сохранены',
        model: function(){
            var Model = require('models/companyOrganization/companyOrganization');

            return new Model();
        }
    });
});