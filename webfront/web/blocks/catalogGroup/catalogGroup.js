define(function(require) {
    //requirements
    var Editor = require('kit/blocks/editor/editor'),
        Catalog__categoryList = require('blocks/catalog/catalog__categoryList'),
        Tooltip_catalogGroupMenu = require('blocks/tooltip/tooltip_catalogGroupMenu/tooltip_catalogGroupMenu'),
        Tooltip_catalogCategoryForm = require('blocks/tooltip/tooltip_catalogCategoryForm/tooltip_catalogCategoryForm'),
        Tooltip_catalogCategoryMenu = require('blocks/tooltip/tooltip_catalogCategoryMenu/tooltip_catalogCategoryMenu'),
        CatalogCategoryModel = require('models/catalogCategory'),
        Form_catalogGroupProperties = require('blocks/form/form_catalogGroupProperties/form_catalogGroupProperties'),
        params = require('pages/catalog/params');

    var router = new Backbone.Router();

    return Editor.extend({
        __name__: 'catalogGroup',
        catalogGroupModel: null,
        templates: {
            index: require('tpl!blocks/catalogGroup/templates/index.html'),
            catalog__categoryList: require('tpl!blocks/catalog/templates/catalog__categoryList.html'),
            catalog__categoryItem: require('tpl!blocks/catalog/templates/catalog__categoryItem.html')
        },
        events: {
            'click .catalog__editGroupLink': 'click .catalog__editGroupLink',
            'click .catalog__addCategoryLink': 'click .catalog__addCategoryLink'
        },
        listeners: {
            catalogGroupModel: {
                'destroy': function() {
                    var block = this;

                    router.navigate('/catalog', {
                        trigger: true
                    });
                }
            }
        },
        'click .catalog__editGroupLink': function(e) {
            e.preventDefault();

            var block = this,
                $target = $(e.target);

            block.tooltip_catalogGroupMenu.show({
                $trigger: $target,
                catalogGroupModel: block.catalogGroupModel
            });
        },
        'click .catalog__addCategoryLink': function(e) {
            e.preventDefault();

            var block = this,
                $target = $(e.target);

            block.tooltip_catalogCategoryForm.show({
                $trigger: $target,
                collection: block.catalogGroupModel.categories,
                model: new CatalogCategoryModel({
                    group: block.catalogGroupModel.id
                })
            });
        },
        initialize: function() {
            var block = this;

            Editor.prototype.initialize.call(block);

            block.tooltip_catalogGroupMenu = new Tooltip_catalogGroupMenu();
            block.tooltip_catalogCategoryForm = new Tooltip_catalogCategoryForm();
            block.tooltip_catalogCategoryMenu = new Tooltip_catalogCategoryMenu();

            new Catalog__categoryList({
                el: document.getElementById('catalog__categoryList'),
                catalogCategoriesCollection: block.catalogGroupModel.categories
            });

            new Form_catalogGroupProperties({
                el: document.getElementById('form_catalogGroupProperties'),
                model: block.catalogGroupModel
            })
        },
        'set:editMode': function(editMode) {
            Editor.prototype['set:editMode'].apply(this, arguments);
            params.editMode = editMode;
        },
        remove: function(){
            var block = this;

            block.tooltip_catalogCategoryForm.remove();
            block.tooltip_catalogCategoryMenu.remove();
            block.tooltip_catalogGroupMenu.remove();

            Editor.prototype.remove.call(block);
        }
    });
});