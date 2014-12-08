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
        posUrl: function(){
            var block = this,
                stores = _.filter(block.resources.firstStart.get('stores'), function(storeItem) {
                return storeItem.inventoryCostOfGoods;
            });

            return '/pos' + (stores.length == 1 ? '/stores/' + stores[0].store.id : '') + '?firstStart=1';
        },
        render: function() {

            var block = this;

            var step1 = !block.resources.firstStart.data.length;

            var step2 = _.find(block.resources.firstStart.get('stores'), function(storeItem) {
                return storeItem.store;
            });

            var step3 = _.find(block.resources.firstStart.get('stores'), function(storeItem) {
                return storeItem.inventoryCostOfGoods;
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