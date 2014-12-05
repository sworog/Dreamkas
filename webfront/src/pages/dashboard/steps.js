define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./steps.ejs'),
        resources: {
            firstStart: require('resources/firstStart/firstStart')
        },
        initialize: function(){

            var block = this;

            block.listenTo(this.resources.firstStart, {
                fetched: function(){
                    block.render();
                }
            });

            return Block.prototype.initialize.apply(block, arguments);
        },
        render: function(){

            this.activeStep = 1;

            return Block.prototype.render.apply(this, arguments);
        },
        blocks: {
            modal_store: require('blocks/modal/store/store')
        }
    });
});