define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_cashFlow',
        cashFlowId: 0,
        blocks: {
            removeButton: function(){

                var block = this,
                    RemoveButton = require('blocks/removeButton/removeButton');

                return new RemoveButton({
                    model: block.model,
                    removeText: 'Удалить операцию'
                });
            },
            inputDate: require('blocks/inputDate/inputDate')
        },
        model: function() {
            var CashFlowModel = require('resources/cashFlow/model');

            return PAGE.get('collections.cashFlows').get(this.cashFlowId) || new CashFlowModel;
        },
        collection: function() {
            return PAGE.get('collections.cashFlows');
        }
    });
});