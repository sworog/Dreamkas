define(function(require, exports, module) {
    //requirements
	var Block = require('kit/block/block'),
		URI = require('uri');

    return Block.extend({
        template: require('ejs!./group__breadcrumbs.ejs'),
		collections: {
			stockSell:	function() {
				return PAGE.collections.stockSell;
			}
		},
		models: {
			group: function() {
				return PAGE.models.group;
			}
		},
		initialize: function() {
			var block = this;

			Block.prototype.initialize.apply(block, arguments);

			block.listenTo(block.collections.stockSell, {
				'change reset': function() {
					block.render();
				}
			});
		},

		allGroupsUrl: function() {

			return URI('/reports/stockSell')
				.search(this.collections.stockSell.filters).toString();
		}
    });
});