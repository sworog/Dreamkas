define(function(require, exports, module) {
	//requirements
	var Modal = require('blocks/modal/modal');

	return Modal.extend({
		template: require('ejs!./template.ejs'),
		id: 'modal_writeOff',
		writeOffId: null,
		models: {
			writeOff: null
		},
		events: {
			'click .writeOff__removeLink': function(e) {
				var block = this;

				e.target.classList.add('loading');

				block.models.writeOff.destroy().then(function() {
					e.target.classList.remove('loading');
					block.hide();
				});
			}
		},
		blocks: {
			form_writeOff: function(opt) {
				var Form_writeOff = require('blocks/form/writeOff/writeOff');

				opt.model = this.models.writeOff;

				return new Form_writeOff(opt);
			},
			form_writeOffProducts: function(opt) {
				var block = this,
					Form_writeOffProducts = require('blocks/form/stockMovementProducts/writeOff/writeOff');

				opt.collection = block.models.writeOff.collections.products;

				return new Form_writeOffProducts(opt);
			}
		},
		render: function(data) {
			var WriteOffModel = require('resources/writeOff/model');

			this.models.writeOff = PAGE.collections.stockMovements.get(data && data.writeOffId) || new WriteOffModel;

			Modal.prototype.render.apply(this, arguments);
		}
	});
});