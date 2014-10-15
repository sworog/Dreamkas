define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block/block');

    return Block.extend({
        template: require('ejs!./template.ejs'),
        all: '0',
        selected: null,
        collection: function(){
            return PAGE.collections.stores;
        }
    });
});