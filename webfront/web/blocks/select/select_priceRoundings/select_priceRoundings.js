define(function(require) {
        //requirements
        var Select = require('kit/blocks/select/select');

        return Select.extend({
            __name__: 'select_priceRoundings',
            template: require('tpl!blocks/select/select_priceRoundings/templates/index.html')
        });
    }
);