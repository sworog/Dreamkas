define(function(require) {
        //requirements
        var Form = require('kit/form');

        return Form.extend({
            model: require('models/store'),
            redirectUrl: '/stores',
            template: require('rv!./template.html'),
            data: {
                model: {}
            },
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