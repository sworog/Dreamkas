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
			},
			'hide input[name="dateFrom"]': function(e) {
				this.onValuesChange();
			},
			'hide input[name="dateTo"]': function(e) {
				this.onValuesChange();
			}
		},
        render: function(){
            var block = this;

            Block.prototype.render.apply(block, arguments);

            block.$el.datepicker({
					language: 'ru',
					format: 'dd.mm.yyyy',
					autoclose: true,
					todayBtn: 'linked'
            	});
        },
		onValuesChange: function() {
			var block = this,
				dateFromInput = this.$el.find('input[name="dateFrom"]'),
				dateToInput = this.$el.find('input[name="dateTo"]');

			//Fix of deselect in bootstrap-datepicker. Предотвращаем deselect, возвращая предыдущее значение и делая render заново.
			if (!dateFromInput.val()) {
				dateFromInput.val(block.dateFrom);
			}
			if (!dateToInput.val()) {
				dateToInput.val(block.dateTo);
			}

			block.dateFrom = dateFromInput.val();
			block.dateTo = dateToInput.val();

			block.$el.datepicker('remove');
			block.render();

			block.trigger('change:values', {
				dateFrom: block.dateFrom,
				dateTo: block.dateTo
			});

            block.$el.trigger('update');
		}
    });
});