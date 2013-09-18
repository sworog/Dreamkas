define(function(require) {
    //requirements
    var Block = require('kit/core/block');

    return Block.extend({
        __name__: 'user',
        model: null,
        userId: null,
        template: require('tpl!blocks/user/templates/index.html'),
        listeners: {
            model: {
                change: function(){
                    var block = this;

                    block.render();
                }
            }
        }
    });
});