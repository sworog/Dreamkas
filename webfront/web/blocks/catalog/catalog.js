define(function(require) {

        //requirements
        var Editor = require('kit/blocks/editor/editor'),
            CatalogGroupModel = require('models/catalogGroup'),
            Catalog__groupList = require('blocks/catalog/catalog__groupList/catalog__groupList'),
            Tooltip_catalogGroupForm = require('blocks/tooltip/tooltip_catalogGroupForm/tooltip_catalogGroupForm'),
            params = require('pages/catalog/params');

        return Editor.extend({
            blockName: 'catalog',
            catalogGroupsCollection: null,
            templates: {
                index: require('tpl!blocks/catalog/templates/index.html')
            },
            events: {
                'click .catalog__addGroupLink': function(e) {
                    e.preventDefault();

                    var block = this,
                        $trigger = $(e.target);

                    block.tooltip_catalogGroupForm.show({
                        $trigger: $trigger,
                        catalogGroupsCollection: block.catalogGroupsCollection,
                        catalogGroupModel: new CatalogGroupModel(),
                        align: function(){
                            var tooltip = this;

                            tooltip.$el
                                .css({
                                    top: tooltip.$trigger.offset().top - 15,
                                    left: tooltip.$trigger.offset().left
                                })
                        }
                    });
                }
            },
            initialize: function() {
                var block = this;

                Editor.prototype.initialize.call(block);

                block.tooltip_catalogGroupForm = new Tooltip_catalogGroupForm();

                new Catalog__groupList({
                    el: block.el.getElementsByGroupName('catalog__groupList'),
                    catalogGroupsCollection: block.catalogGroupsCollection
                });
            },
            'set:editMode': function(editMode){
                Editor.prototype['set:editMode'].apply(this, arguments);
                params.editMode = editMode;
            },
            remove: function(){
                var block = this;

                block.tooltip_catalogGroupForm.remove();

                Editor.prototype.remove.call(block);
            }
        })
    }
);