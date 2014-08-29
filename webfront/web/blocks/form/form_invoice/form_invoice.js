define(function(require, exports, module) {
    //requirements
    var From = require('kit/form/form.deprecated');

    return From.extend({
        el: '.form_invoice',
        model: require('models/invoice/invoice'),
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
            form_invoiceProducts: function(){
                var block = this,
                    Form_invoiceProducts = require('blocks/form/form_invoiceProducts/form_invoiceProducts');

                return new Form_invoiceProducts({
                    el: block.$el.closest('.modal').find('.form_invoiceProducts'),
                    collection: block.model.collections.products
                });
            }
        }
    });
});