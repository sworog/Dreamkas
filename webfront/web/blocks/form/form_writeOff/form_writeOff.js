define(function(require, exports, module) {
    //requirements
    var From = require('kit/form/form.deprecated');

    return From.extend({
        el: '.form_writeOff',
        model: require('models/writeOff/writeOff'),
        blocks: {
            datepicker: function(){
                var block = this;

                block.$('.inputDate, .input-daterange').each(function(){
                    $(this).datepicker({
                        language: 'ru',
                        format: 'dd.mm.yyyy',
                        autoclose: true,
                        endDate: this.dataset.endDate && this.dataset.endDate.toString(),
                        todayBtn: "linked"
                    });
                });
            },
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