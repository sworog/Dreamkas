define(function(require) {
    //requirements
    var Block = require('kit/block');

    return Block.extend({
        blockName: 'catalogCategory__subcategoryList',
        catalogSubcategoriesCollection: null,
        templates: {
            index: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryList.html')
        },
        listeners: {
            catalogSubcategoriesCollection: {
                add: function(){
                    var block = this;

                    block.render();
                }
            }
        }
    });
});