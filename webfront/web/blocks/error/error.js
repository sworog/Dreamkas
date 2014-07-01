define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block'),
        _ = require('lodash');

    return Block.extend({
        el: document.body,

        template: require('ejs!./template.ejs'),
        jsErrors: [],
        apiErrors: [],

        initialize: function(data){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            if (window.ERROR){

                data = _.extend({
                    jsErrors: [],
                    apiErrors: []
                }, data);

                window.ERROR.jsErrors.concat(data.jsErrors);
                window.ERROR.apiErrors.concat(data.apiErrors);

                window.ERROR.render();

                block.destroy();
                return;
            }

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