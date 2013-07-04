define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Catalog__groupList = require('blocks/catalog/catalog__groupList/catalog__groupList'),
        CatalogGroupsCollection = require('collections/catalogGroups');

    return Block.extend({
        catalogClassModel: null,
        blockName: 'catalog__classItem',
        templates: {
            index: require('tpl!./templates/index.html')
        },
        initialize: function() {
            var block = this;

            Block.prototype.initialize.call(block);

            new Catalog__groupList({
                catalogGroupsCollection: block.catalogClassModel.groups || new CatalogGroupsCollection([], {
                    parentClassModel: block.catalogClassModel
                }),
                el: block.el.getElementsByClassName('catalog__groupList')
            });
        }
    });
});