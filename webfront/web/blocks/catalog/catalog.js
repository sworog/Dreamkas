define(function(require) {

        //requirements
        var Editor = require('kit/blocks/editor/editor'),
            CatalogGroupModel = require('models/catalogGroup'),
            Catalog__groupList = require('blocks/catalog/catalog__groupList'),
            Tooltip_catalogGroupForm = require('blocks/tooltip/tooltip_catalogGroupForm/tooltip_catalogGroupForm'),
            Tooltip_catalogGroupMenu = require('blocks/tooltip/tooltip_catalogGroupMenu/tooltip_catalogGroupMenu'),
            Tooltip_catalogCategoryMenu = require('blocks/tooltip/tooltip_catalogCategoryMenu/tooltip_catalogCategoryMenu'),
            params = require('pages/catalog/params');

        return Editor.extend({
            __name__: 'catalog',
            catalogGroupsCollection: null,
            templates: {
                index: require('tpl!blocks/catalog/templates/index.html'),
                catalog__groupList: require('tpl!blocks/catalog/templates/catalog__groupList.html'),
                catalog__groupItem: require('tpl!blocks/catalog/templates/catalog__groupItem.html'),
                catalog__categoryList: require('tpl!blocks/catalog/templates/catalog__categoryList.html'),
                catalog__categoryItem: require('tpl!blocks/catalog/templates/catalog__categoryItem.html')
            },
            events: {
                'click .catalog__addGroupLink': function(e) {
                    e.preventDefault();

                    var block = this,
                        $target = $(e.target);

                    block.tooltip_catalogGroupForm.show({
                        $trigger: $target,
                        collection: block.catalogGroupsCollection,
                        model: new CatalogGroupModel()
                    });
                }
            },
            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);

                block.tooltip_catalogGroupForm = new Tooltip_catalogGroupForm();
                block.tooltip_catalogGroupMenu = new Tooltip_catalogGroupMenu();
                block.tooltip_catalogCategoryMenu = new Tooltip_catalogCategoryMenu();

                new Catalog__groupList({
                    el: document.getElementById('catalog__groupList'),
                    catalogGroupsCollection: block.catalogGroupsCollection
                });

            },
            'set:editMode': function(editMode) {
                Editor.prototype['set:editMode'].apply(this, arguments);
                params.editMode = editMode;
            },
            remove: function(){
                var block = this;

                block.tooltip_catalogGroupForm.remove();
                block.tooltip_catalogGroupMenu.remove();
                block.tooltip_catalogCategoryMenu.remove();

                Editor.prototype.remove.call(block);
            }
        })
    }
);