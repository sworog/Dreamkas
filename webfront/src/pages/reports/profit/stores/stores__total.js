define(function(require, exports, module) {
    //requirements
	var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./stores__total.ejs'),
		collections: {
			profit:	function() {
				return PAGE.collections.profit;
			}
		},
		initialize: function() {
			var block = this;

			Block.prototype.initialize.apply(block, arguments);

			block.listenTo(block.collections.profit, {
				'change reset': function() {
					block.render();
				}
			});
		}
    });
});