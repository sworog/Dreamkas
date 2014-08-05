define(function(require) {
        //requirements
        var Form = require('blocks/form/form');

        return Form.extend({
            __name__: 'form_department',
            storeModel: null,
            template: require('ejs!blocks/form/form_department/templates/index.html'),
            initialize: function(){
                var block = this;

                block.redirectUrl = '/stores/' + block.storeModel.id;

                if (block.model.id){
                    block.redirectUrl += '/departments/' + block.model.id;
                }
            }
        });
    }
);