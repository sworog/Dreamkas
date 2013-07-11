define(function(require) {
    //requirements
    var Block = require('kit/block');

    return Block.extend({
        blockName: 'catalogCategory__subcategoryItem',
        catalogSubcategoryModel: null,
        templates: {
            index: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryItem.html')
        }
    });
});