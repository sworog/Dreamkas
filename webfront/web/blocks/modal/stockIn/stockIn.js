define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
		id: 'modal_stockIn',
		stockinId: null,
        models: {
            stockIn: null
        },
        events: {
            'click .stockIn__removeLink': function(e){
                var block = this;

                e.target.classList.add('loading');

                block.models.stockIn.destroy().then(function() {
                    e.target.classList.remove('loading');
					block.hide();
				});
            }
        },
        blocks: {
            form_stockIn: function(opt){
                var Form_stockIn = require('blocks/form/stockIn/stockIn');

				opt.model = this.models.stockIn;

                return new Form_stockIn(opt);
            },
            form_stockInProducts: function(opt){
                var Form_stockInProducts = require('blocks/form/stockInProducts/stockInProducts');

				return new Form_stockInProducts({
					collection: this.models.stockIn.collections.products
				});
            }
        },
		render: function(data){
			var StockInModel = require('resources/stockIn/model');

			this.models.stockIn = PAGE.collections.stockMovements.get(data && data.stockinId) || new StockInModel;

			Modal.prototype.render.apply(this, arguments);
		}
    });
});