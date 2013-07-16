define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form'),
            _ = require('underscore');

        return Form.extend({
            blockName: 'form_store',
            redirectUrl: '/stores',
            templates: {
                index: require('tpl!blocks/form/form_store/templates/index.html')
            }
        });
    }
);