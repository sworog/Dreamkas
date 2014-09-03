define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    require('datepicker');
    require('i18n!nls/datepicker');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        endDate: null,
        value: null,
        render: function(){
            var block = this;

            Block.prototype.render.apply(block, arguments);

            block.$('input').datepicker({
                language: 'ru',
                format: 'dd.mm.yyyy',
                autoclose: true,
                endDate: block.endDate && block.endDate.toString(),
                todayBtn: "linked"
            });
        },
        destroy: function(){
            var block = this;

            block.$('input').datepicker('remove');

            Block.prototype.destroy.apply(block, arguments);
        }
    });
});