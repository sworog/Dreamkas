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

                block.data.types = select.value;

                select.classList.add('loading');

                $.when(this.submit()).then(function(){
                    select.classList.remove('loading');
                }, function(){
                    select.classList.remove('loading');
                });
            },
            'update .inputDateRange': function(e){

                var block = this,
                    inputBlock = e.target;

                block.data.dateFrom = inputBlock.querySelector('[name="dateFrom"]').value;
                block.data.dateTo = inputBlock.querySelector('[name="dateTo"]').value;

                inputBlock.classList.add('loading');

                $.when(this.submit()).then(function(){
                    inputBlock.classList.remove('loading');
                }, function(){
                    inputBlock.classList.remove('loading');
                });
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
            block.render({
                data: _.pick(PAGE.params, 'dateFrom', 'dateTo', 'types')
            });
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