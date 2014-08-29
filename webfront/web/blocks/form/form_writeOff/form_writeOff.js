define(function(require, exports, module) {
    //requirements
    var From = require('kit/form/form');

    return From.extend({
        template: require('ejs!./template.ejs'),
        model: require('models/writeOff/writeOff'),
        partials: {
            select_stores: require('ejs!blocks/select/select_stores/select_stores.ejs')
        },
        blocks: {
            inputDate: require('blocks/inputDate/inputDate'),
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