define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form');

        return Form.extend({
            __name__: 'form_store',
            redirectUrl: '/stores/',
            templates: {
                index: require('tpl!blocks/form/form_store/templates/index.html')
            },
            initialize: function(){
                var block = this;

                if (block.model.id){
                    block.redirectUrl += block.model.id;
                }
            }
        });
    }
);