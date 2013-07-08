define(function(require) {
        //requirements
        var Select = require('kit/blocks/select/select');

        return Select.extend({
            templates: {
                index: require('tpl!blocks/select/select_userRole/templates/index.html')
            }
        });
    }
);