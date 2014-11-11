define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        selected: null,
        collection: function(){
            return PAGE.collections.groups;
        }
    });
});