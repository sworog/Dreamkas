define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        router = require('router');

    return Form.extend({
        template: require('ejs!./form_stockMovementsFilters.ejs'),
        data: function(){
            return _.pick(PAGE.params, 'dateFrom', 'dateTo', 'types');
        },
        events: {
            reset: function(){
                var block = this,
                    $resetButton = block.$('[type="reset"]'),
                    filters = {
                        types: '',
                        dateFrom: '',
                        dateTo: ''
                    };

                $resetButton.addClass('loading');

                PAGE.collections.stockMovements.filters = filters;

                PAGE.collections.stockMovements.fetch().then(function(){
                    PAGE.setParams(filters);
                });
            }
        },
        blocks: {
            inputDateRange: require('blocks/inputDateRange/inputDateRange')
        },
        submit: function(){
            var block = this;

            PAGE.collections.stockMovements.filters = block.data;

            return PAGE.collections.stockMovements.fetch();
        },
        submitSuccess: function(){
            var block = this;

            PAGE.setParams(block.data);
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