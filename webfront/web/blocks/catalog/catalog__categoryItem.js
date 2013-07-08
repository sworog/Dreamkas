define(function(require) {
    //requirements
    var Block = require('kit/block');

    return Block.extend({
        catalogCategoryModel: null,
        templates: {
            index: require('tpl!blocks/catalog/templates/catalog__categoryItem.html')
        }
    });
});