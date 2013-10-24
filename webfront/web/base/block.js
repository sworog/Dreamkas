define(function(require) {
    //requirements
    var Block = require('kit/core/block');

    return Block.extend({
        dictionary: require('dictionary')
    });
});