define(function(require) {
        //requirements
        var Select = require('kit/blocks/select/select');

        return Select.extend({
            blockName: 'select_vat',
            templates: {
                index: require('tpl!blocks/select/select_vat/templates/index.html')
            }
        });
    }
);