define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_supplier',
        collection: function() {
            return PAGE.get('collections.suppliers');
        },
        blocks: {
            removeButton: function(){

                var block = this,
                    RemoveButton = require('blocks/removeButton/removeButton');

                return new RemoveButton({
                    model: block.model,
                    removeText: 'Удалить поставщика'
                });
            }
        }
    });
});