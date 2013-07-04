define(function(require) {

        //requirements
        var Editor = require('kit/blocks/editor/editor'),
            CatalogClassModel = require('models/catalogClass'),
            Catalog__classList = require('blocks/catalog/catalog__classList/catalog__classList'),
            Tooltip_catalogClassForm = require('blocks/tooltip/tooltip_catalogClassForm/tooltip_catalogClassForm'),
            params = require('pages/catalog/params');

        return Editor.extend({
            blockName: 'catalog',
            catalogClassesCollection: null,
            templates: {
                index: require('tpl!./templates/index.html')
            },
            events: {
                'click .catalog__addClassLink': function(e) {
                    e.preventDefault();

                    var block = this,
                        $trigger = $(e.target);

                    block.tooltip_catalogClassForm.show({
                        $trigger: $trigger,
                        catalogClassesCollection: block.catalogClassesCollection,
                        catalogClassModel: new CatalogClassModel(),
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

                block.tooltip_catalogClassForm = new Tooltip_catalogClassForm();

                new Catalog__classList({
                    el: block.el.getElementsByClassName('catalog__classList'),
                    catalogClassesCollection: block.catalogClassesCollection
                });
            },
            'set:editMode': function(editMode){
                Editor.prototype['set:editMode'].apply(this, arguments);
                params.editMode = editMode;
            },
            remove: function(){
                var block = this;

                block.tooltip_catalogClassForm.remove();

                Editor.prototype.remove.call(block);
            }
        })
    }
);