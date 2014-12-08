define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./steps.ejs'),
        resources: {
            firstStart: require('resources/firstStart/firstStart')
        },
        initialize: function() {

            var block = this;

            block.listenTo(this.resources.firstStart, {
                fetched: function() {
                    block.render();
                }
            });

            return Block.prototype.initialize.apply(block, arguments);
        },
        render: function() {

            var block = this;

            var step1 = !block.resources.firstStart.data.length;

            var step2 = _.find(block.resources.firstStart.data, function(item) {
                return item && item.store;
            });

            var step3 = _.find(block.resources.firstStart.data, function(item) {
                return item && item.store && item.store.costOfGoods;
            });

            if (step1) {
                this.activeStep = 1;
            }

            if (step2) {
                this.activeStep = 2;
            }

            if (step3) {
                this.activeStep = 3;
            }

            return Block.prototype.render.apply(this, arguments);
        }
    });
});