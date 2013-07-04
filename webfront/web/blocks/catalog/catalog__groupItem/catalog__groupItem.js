define(function(require) {
    //requirements
    var Block = require('kit/block');

    return Block.extend({
        catalogGroupModel: null,
        templates: {
            index: require('tpl!./templates/index.html')
        }
    });
});