define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form');

        return Form.extend({
            blockName: 'form_store',
            redirectUrl: function(){
                var block = this;

                return '/stores/' + block.model.id;
            },
            templates: {
                index: require('tpl!blocks/form/form_store/templates/index.html')
            }
        });
    }
);