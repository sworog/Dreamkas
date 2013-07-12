define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        Editor = require('kit/blocks/editor/editor'),
        CatalogSubcategoryModel = require('models/catalogSubcategory'),
        CatalogCategory__subcategoryList = require('blocks/catalogCategory/catalogCategory__subcategoryList'),
        Tooltip_catalogCategoryMenu = require('blocks/tooltip/tooltip_catalogCategoryMenu/tooltip_catalogCategoryMenu'),
        Tooltip_catalogSubcategoryMenu = require('blocks/tooltip/tooltip_catalogSubcategoryMenu/tooltip_catalogSubcategoryMenu'),
        Tooltip_catalogSubcategoryForm = require('blocks/tooltip/tooltip_catalogSubcategoryForm/tooltip_catalogSubcategoryForm'),
        params = require('pages/catalog/params');

    var router = new Backbone.Router();

    return Editor.extend({
        blockName: 'catalogCategory',
        catalogCategoryModel: null,
        catalogSubcategoryId: null,
        catalogSubcategoriesCollection: null,
        templates: {
            index: require('tpl!blocks/catalogCategory/templates/index.html'),
            catalogCategory__subcategoryList: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryList.html'),
            catalogCategory__subcategoryItem: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryItem.html')
        },
        events: {
            'click .catalog__editCategoryLink': function(e){
                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogCategoryMenu.show({
                    $trigger: $target,
                    catalogCategoryModel: block.catalogCategoryModel
                });
            },
            'click .catalog__addSubcategoryLink': function(e) {
                e.preventDefault();

                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogSubcategoryForm.show({
                    $trigger: $target,
                    collection: block.catalogSubcategoriesCollection,
                    model: new CatalogSubcategoryModel({
                        category: block.catalogCategoryModel.id,
                        group: block.catalogCategoryModel.get('group')
                    })
                });
            },
            'click .catalogCategory__subcategoryLink': function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var block = this,
                    $target = $(e.currentTarget);

                router.navigate($target.attr('href'));

                block.set('catalogSubcategoryId', $target.attr('subcategory_id'));
            }
        },
        listeners: {
            catalogCategoryModel: {
                destroy: function(){
                    var block = this;

                    router.navigate('/catalog/' + block.catalogCategoryModel.get('group'), {
                        trigger: true
                    })
                }
            }
        },
        initialize: function() {
            var block = this;

            Editor.prototype.initialize.call(block);

            if (block.catalogSubcategoryId){
                block.set('catalogSubcategoryId', block.catalogSubcategoryId);
            }

            block.tooltip_catalogCategoryMenu = new Tooltip_catalogCategoryMenu();
            block.tooltip_catalogSubcategoryForm = new Tooltip_catalogSubcategoryForm();
            block.tooltip_catalogSubcategoryMenu = new Tooltip_catalogSubcategoryMenu();

            new CatalogCategory__subcategoryList({
                el: document.getElementById('catalogCategory__subcategoryList'),
                catalogSubcategoriesCollection: block.catalogSubcategoriesCollection
            });
        },
        'set:editMode': function(editMode) {
            Editor.prototype['set:editMode'].apply(this, arguments);
            params.editMode = editMode;
        },
        remove: function(){
            var block = this;

            block.tooltip_catalogCategoryMenu.remove();
            block.tooltip_catalogSubcategoryForm.remove();
            block.tooltip_catalogSubcategoryMenu.remove();

            Editor.prototype.remove.call(block);
        },
        'set:catalogSubcategoryId': function(catalogSubcategoryId) {
            var block = this;

            block.$el
                .find('.catalogCategory__subcategoryLink_active')
                .removeClass('catalogCategory__subcategoryLink_active');

            block.$el
                .find('.catalogCategory__subcategoryLink[subcategory_id="' + catalogSubcategoryId + '"]')
                .addClass('catalogCategory__subcategoryLink_active');
        }
    });
});