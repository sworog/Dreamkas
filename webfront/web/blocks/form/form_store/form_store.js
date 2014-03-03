define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form');

        return Form.extend({
            __name__: 'form_store',
            redirectUrl: '/stores/',
            initialize: function(){
                var block = this;

                if (block.model.id){
                    block.redirectUrl += block.model.id;
                }
            }
        });
    }
);