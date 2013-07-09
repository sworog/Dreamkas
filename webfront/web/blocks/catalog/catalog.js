define(function(require) {

        //requirements
        var Editor = require('kit/blocks/editor/editor'),
            CatalogGroupModel = require('models/catalogGroup'),
            Catalog__groupList = require('blocks/catalog/catalog__groupList'),
            Tooltip_catalogGroupForm = require('blocks/tooltip/tooltip_catalogGroupForm/tooltip_catalogGroupForm'),
            Tooltip_catalogGroupMenu = require('blocks/tooltip/tooltip_catalogGroupMenu/tooltip_catalogGroupMenu'),
            params = require('pages/catalog/params');

        return Editor.extend({
            blockName: 'catalog',
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

                    block.blocks.tooltip_catalogGroupForm.show({
                        $trigger: $target,
                        catalogGroupsCollection: block.catalogGroupsCollection,
                        catalogGroupModel: new CatalogGroupModel()
                    });
                }
            },
            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);

                block.blocks.tooltip_catalogGroupForm = new Tooltip_catalogGroupForm();

                block.blocks.tooltip_catalogGroupMenu = new Tooltip_catalogGroupMenu({
                    blocks: {
                        tooltip_catalogGroupForm: block.blocks.tooltip_catalogGroupForm
                    }
                });

                new Catalog__groupList({
                    el: block.el.getElementsByClassName('catalog__groupList'),
                    catalogGroupsCollection: block.catalogGroupsCollection,
                    blocks: {
                        tooltip_catalogGroupMenu: block.blocks.tooltip_catalogGroupMenu
                    }
                });
            },
            'set:editMode': function(editMode){
                Editor.prototype['set:editMode'].apply(this, arguments);
                params.editMode = editMode;
            }
        })
    }
);