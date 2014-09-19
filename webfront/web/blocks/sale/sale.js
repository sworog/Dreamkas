define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        globalEvents: {
            'click:receipt': function(){
                this.render();
            }
        },
        blocks: {
            modal_refund: require('blocks/modal/refund/refund')
        }
    });
});