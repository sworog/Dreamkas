define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        router = require('router');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        data: function(){
            return _.pick(PAGE.params, 'dateFrom', 'dateTo', 'types');
        },
        events: {
            'change [name="types"]': function(e){

                var block = this,
                    select = e.target;

                select.classList.add('loading');

                block.$el.trigger('submit');

            },
            'update .inputDateRange': function(e){

                var block = this,
                    inputBlock = e.target;

                inputBlock.classList.add('loading');

                block.$el.trigger('submit');
            }
        },
        blocks: {
            inputDateRange: require('blocks/inputDateRange/inputDateRange')
        },
        submit: function(){
            var block = this;

            return PAGE.collections.stockMovements.fetch({
                filters: block.data
            });
        },
        submitSuccess: function(){
            var block = this;

            PAGE.setParams(block.data);

            block.$('.loading').removeClass('loading');

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