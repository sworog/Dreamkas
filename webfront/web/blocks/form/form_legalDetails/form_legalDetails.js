define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form');

    return Form.extend({
        el: '.form_legalDetails',
        successMessage: 'Данные успешно сохранены',
        events: {
            'change [name="legalDetails.type"]': function(e){
                var block = this;

                $(block.el).find('.form_legalDetails__typeFields').attr('disabled', true);
                $(block.el).find('.form_legalDetails__typeFields[rel="' + e.target.value + '"]').removeAttr('disabled');
            }
        },
        model: function(){
            var Model = require('models/companyOrganization/companyOrganization');

            return new Model();
        }
    });
});