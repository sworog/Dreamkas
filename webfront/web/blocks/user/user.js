define(function(require) {
    //requirements
    var Block = require('kit/block');

    return Block.extend({
        blockName: 'user',
        className: 'user',
        model: null,
        userId: null,
        templates: {
            index: require('tpl!blocks/user/templates/index.html')
        },
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