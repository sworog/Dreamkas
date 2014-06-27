define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        el: '.form_companyOrganizationDetails',
        successMessage: 'Данные успешно сохранены',
        events: {
            'change [name="legalDetails.type"]': function(e){
                var block = this;

                $(block.el).find('.form_companyOrganizationDetails__typeFields').attr('disabled', true);
                $(block.el).find('.form_companyOrganizationDetails__typeFields[rel="' + e.target.value + '"]').removeAttr('disabled');
            }
        },
        model: function(){
            var Model = require('models/companyOrganization/companyOrganization');

            return new Model();
        }
    });
});