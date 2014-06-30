define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        el: '.form_companyOrganization',
        redirectUrl: function(){
            var block = this;

            if (!block.get('model.id')){
                return '/company';
            }
        },
        successMessage: 'Данные организации успешно сохранены',
        model: function(){
            var Model = require('models/companyOrganization/companyOrganization');

            return new Model();
        },
        blocks: {
            certificateDateInput: function(){
                var InputDate = require('blocks/inputDate/inputDate');

                return new InputDate({
                    el: '[name="legalDetails.certificateDate"]'
                });
            }
        }
    });
});