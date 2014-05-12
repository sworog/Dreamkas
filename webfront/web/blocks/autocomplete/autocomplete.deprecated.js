define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        cid: module.id,
        initialize: function(){
            var block = this;

            $(block.el).autocomplete({
                source: block.source,
                minLength: 3,
                select: block.select
            });
        },
        select: function(){},
        suorce : function(request, response){}
    });
});