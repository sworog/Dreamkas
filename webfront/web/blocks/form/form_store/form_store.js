define(function(require) {
        //requirements
        var Form = require('kit/form');

        return Form.extend({
            el: '.form_store',
            redirectUrl: '/stores',
            initialize: function(){
                var block = this;

                Form.prototype.initialize.apply(block, arguments);

                if (block.model.id){
                    block.redirectUrl += '/' + block.model.id;
                }
            }
        });
    }
);