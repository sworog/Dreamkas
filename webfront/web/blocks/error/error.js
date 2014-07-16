define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block'),
        _ = require('lodash');

    return Block.extend({
        el: document.body,

        template: require('ejs!./template.ejs'),

        jsError: null,
        apiError: null,

        initialize: function(data){
            var block = this;

            if (window.ERROR){

                _.extend(window.ERROR, data);

                window.ERROR.render();

                block.destroy();
                return;
            }

            Block.prototype.initialize.apply(block, arguments);

            window.ERROR = block;

            block.render();

        },
        close: function(){
            var block = this;

            block.remove();

            delete window.ERROR;
        }
    });
});