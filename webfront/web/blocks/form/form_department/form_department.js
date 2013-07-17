define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form');

        return Form.extend({
            blockName: 'form_department',
            storeModel: null,
            templates: {
                index: require('tpl!blocks/form/form_department/templates/index.html')
            },
            initialize: function(){
                var block = this;

                Form.prototype.initialize.apply(this, arguments);

                block.redirectUrl = '/stores/' + block.storeModel.id;

                if (block.model.id){
                    block.redirectUrl += '/departments/' + block.model.id;
                }
            }
        });
    }
);