define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
		checkKey = require('kit/checkKey/checkKey');

    require('datepicker');
    require('i18n!nls/datepicker');

    return Block.extend({
        template: require('ejs!./inputDateRange.ejs'),
        dateFrom: null,
        dateTo: null,
        endDate: null,
		events: {
			'keydown input': function(e) {
				return false;
			},
			'keyup input': function(e) {
				e.stopPropagation();
			}
		},
        render: function(){
            var block = this;

            Block.prototype.render.apply(block, arguments);

            block.$el.datepicker({
                language: 'ru',
                format: 'dd.mm.yyyy',
                autoclose: true,
                endDate: block.endDate && block.endDate.toString(),
                todayBtn: 'linked'
            });
        },
        destroy: function(){
            var block = this;

            block.$el.datepicker('remove');

            Block.prototype.destroy.apply(block, arguments);
        }
    });
});