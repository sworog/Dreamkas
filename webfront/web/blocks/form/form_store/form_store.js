define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form');

        return Form.extend({
            blockName: 'form_store',
            templates: {
                index: require('tpl!blocks/form/form_store/templates/index.html')
            },
            initialize: function(){
                var block = this;

                Form.prototype.initialize.apply(block, arguments);

                block.redirectUrl = '/stores/';

                if (block.model.id){
                    block.redirectUrl += block.model.id;
                }
            }
        });
    }
);