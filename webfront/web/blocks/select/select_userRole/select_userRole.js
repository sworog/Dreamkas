define(function(require) {
        //requirements
        var Select = require('kit/blocks/select/select');

        return Select.extend({
            __name__: 'select_userRole',
            template: require('tpl!blocks/select/select_userRole/templates/index.html')
        });
    }
);