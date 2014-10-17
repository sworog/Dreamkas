define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./stores__total.ejs'),
        models: {
            profit: function() {

                var block = this,
                    model = PAGE.models.profit;

                block.listenTo(model, {
                    change: function() {
                        block.render();
                    }
                });

                return model;
            }
        }
    });
});