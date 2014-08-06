define(function(require) {
        //requirements
        var Block = require('kit/block/block');

        require('datepicker');
        require('i18n!nls/datepicker');

        return Block.extend({
            el: '.inputDate',
            initialize: function() {
                var block = this;

                Block.prototype.initialize.apply(block, arguments);

                block.$el.datepicker({
                    language: 'ru',
                    format: 'dd.mm.yyyy',
                    autoclose: true
                });
            },
            remove: function() {

                var block = this;

                Block.prototype.remove.apply(block, arguments);

                block.$el.datepicker('remove')
            }
        });
    }
);