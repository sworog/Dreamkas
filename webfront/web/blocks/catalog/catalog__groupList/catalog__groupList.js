define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Catalog__groupItem = require('blocks/catalog/catalog__groupItem/catalog__groupItem');

    return Block.extend({
        catalogGroupsCollection: null,
        templates: {
            index: require('tpl!./templates/index.html')
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.call(block);

            block.catalogGroupsCollection.each(function(catalogGroupModel){
                new Catalog__groupItem({
                    catalogGroupModel: catalogGroupModel,
                    el: block.el.querySelectorAll('[groupId="' + catalogGroupModel.id + '"]')
                })
            });
        }
    });
});