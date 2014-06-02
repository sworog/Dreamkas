define(function(require) {
        //requirements
        var Form = require('kit/form');

        return Form.extend({
            redirectUrl: '/stores',
            template: require('rv!./template.html'),
            init: function(){
                var block = this;

                block._super();

                if (block.get('model.id')){
                    block.redirectUrl += '/' + block.model.id;
                }
            }
        });
    }
);