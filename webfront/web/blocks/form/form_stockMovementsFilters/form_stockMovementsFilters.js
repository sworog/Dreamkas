define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form'),
        router = require('router');

    return Form.extend({
        el: '.form_stockMovementsFilters',
        submit: function(){
            var block = this;

            PAGE.collections.stockMovements.filters = block.formData;

            return PAGE.collections.stockMovements.fetch();
        },
        submitSuccess: function(){
            var block = this;

            router.save({
                filters: block.formData
            });
            
            PAGE.render();
        },
        showErrors: function(error){
            var block = this,
                errors = error.errors,
                errorList = [];

            if (errors.children) {
                _.each(errors.children, function(data, field) {

                    if (data.errors && data.errors.length){
                        block.$('[name="' + field + '"]').addClass('invalid');
                        errorList = _.union(errorList, data.errors);
                    }
                });
            }

            block.showGlobalError(errorList);

        }
    });
});