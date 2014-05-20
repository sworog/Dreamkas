define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    require('lodash');

    return Form.extend({
        el: '.form_barcodes',
        events: {
            'click .form_barcodes__addLink': function() {
                var block = this,
                    addRow = block.el.querySelector('tfoot tr');

                block.el.querySelector('tbody').appendChild(addRow.cloneNode(true));

                _.forEach(addRow.querySelectorAll('input'), function(input){
                    input.value = "";
                });
            }
        }
    });
});