define(function(require) {
        //requirements
        var Select = require('kit/blocks/select/select');

        return Select.extend({
            __name__: 'select_unit',
            templates: {
                index: require('tpl!blocks/select/select_unit/templates/index.html')
            }
        });
    }
);