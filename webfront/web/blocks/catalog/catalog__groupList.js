define(function(require) {
    //requirements
    var Block = require('kit/core/block'),
        Catalog__groupItem = require('blocks/catalog/catalog__groupItem');

    return Block.extend({
        __name__: 'catalog__groupList',
        catalogGroupsCollection: null,
        template: require('tpl!blocks/catalog/templates/catalog__groupList.html'),
        templates: {
            index: require('tpl!blocks/catalog/templates/catalog__groupList.html'),
            catalog__groupItem: require('tpl!blocks/catalog/templates/catalog__groupItem.html'),
            catalog__categoryList: require('tpl!blocks/catalog/templates/catalog__categoryList.html'),
            catalog__categoryItem: require('tpl!blocks/catalog/templates/catalog__categoryItem.html')
        },
        listeners: {
            catalogGroupsCollection: {
                add: function(model, collection, options){
                    var block = this,
                        catalogGroupItem = new Catalog__groupItem({
                            catalogGroupModel: model
                        });

                    block.$el.prepend(catalogGroupItem.el);
                }
            }
        },
        initialize: function(){
            var block = this;

            block.catalogGroupsCollection.each(function(catalogGroupModel){
                new Catalog__groupItem({
                    catalogGroupModel: catalogGroupModel,
                    el: block.el.querySelectorAll('[group_id="' + catalogGroupModel.id + '"]')
                });
            })
        }
    });
});