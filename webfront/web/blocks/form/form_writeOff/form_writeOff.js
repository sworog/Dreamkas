define(function(require, exports, module) {
    //requirements
    var From = require('kit/form/form');

    return From.extend({
        el: '.form_writeOff',
        model: function(){
            var block = this,
                WriteOffModel = require('models/writeOff/writeOff');

            return new WriteOffModel();
        },
        blocks: {
            form_writeOffProducts: function(){
                var block = this,
                    Form_writeOffProducts = require('blocks/form/form_writeOffProducts/form_writeOffProducts');

                return new Form_writeOffProducts({
                    el: block.$el.closest('.modal').find('.form_writeOffProducts'),
                    collection: block.model.collections.products
                });
            }
        }
    });
});