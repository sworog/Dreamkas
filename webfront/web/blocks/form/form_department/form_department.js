define(function(require) {
        //requirements
        var Form = require('kit/blocks/form/form');

        return Form.extend({
            blockName: 'form_store',
            storeModel: null,
            templates: {
                index: require('tpl!blocks/form/form_store/templates/index.html')
            },
            initialize: function(){
                var block = this;

                Form.prototype..initialize.apply(this, arguments);

                block.redirectUrl = '/stores/' + block.storeModel.id;
            }
        });
    }
);