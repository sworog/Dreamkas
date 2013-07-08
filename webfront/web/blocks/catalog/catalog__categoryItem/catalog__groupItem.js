define(function(require) {
    //requirements
    var Block = require('kit/block');

    return Block.extend({
        catalogCategoryModel: null,
        templates: {
            index: require('tpl!blocks/catalog/catalog__categoryItem/templates/index.html')
        }
    });
});