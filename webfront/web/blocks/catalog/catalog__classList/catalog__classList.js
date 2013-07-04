define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Catalog__classItem = require('blocks/catalog/catalog__classItem/catalog__classItem');

    return Block.extend({
        catalogClassesCollection: null,
        templates: {
            index: require('tpl!./templates/index.html')
        },
        listeners: {
            catalogClassesCollection: {
                add: function(model, collection, options){
                    var block = this,
                        catalogClassItem = new Catalog__classItem({
                            catalogClassModel: model
                        });

                    block.$el.prepend(catalogClassItem.el);
                }
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.call(block);

            block.catalogClassesCollection.each(function(catalogClassModel){
                new Catalog__classItem({
                    catalogClassModel: catalogClassModel,
                    el: block.el.querySelectorAll('[classId="' + catalogClassModel.id + '"]')
                });
            })
        }
    });
});