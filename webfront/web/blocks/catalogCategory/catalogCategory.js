define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        Editor = require('kit/blocks/editor/editor'),
        CatalogSubcategoryModel = require('models/catalogSubcategory'),
        CatalogCategory__subcategoryList = require('blocks/catalogCategory/catalogCategory__subcategoryList'),
        Tooltip_catalogCategoryMenu = require('blocks/tooltip/tooltip_catalogCategoryMenu/tooltip_catalogCategoryMenu'),
        Tooltip_catalogSubcategoryMenu = require('blocks/tooltip/tooltip_catalogSubcategoryMenu/tooltip_catalogSubcategoryMenu'),
        Tooltip_catalogSubcategoryForm = require('blocks/tooltip/tooltip_catalogSubcategoryForm/tooltip_catalogSubcategoryForm'),
        Table_products = require('blocks/table/table_products/table_products'),
        params = require('pages/catalog/params');

    var router = new Backbone.Router();

    return Editor.extend({
        blockName: 'catalogCategory',

        catalogCategoryModel: null,
        catalogSubcategoryId: null,
        catalogSubcategoriesCollection: null,
        catalogProductsCollection: null,

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

            block.tooltip_catalogCategoryMenu = new Tooltip_catalogCategoryMenu();
            block.tooltip_catalogSubcategoryForm = new Tooltip_catalogSubcategoryForm();
            block.tooltip_catalogSubcategoryMenu = new Tooltip_catalogSubcategoryMenu();

            block.table_products = new Table_products({
                el: block.el.getElementsByClassName('table_products'),
                collection: block.catalogProductsCollection
            });

            new CatalogCategory__subcategoryList({
                el: document.getElementById('catalogCategory__subcategoryList'),
                catalogSubcategoriesCollection: block.catalogSubcategoriesCollection
            });

            block.set('catalogSubcategoryId', block.catalogSubcategoryId);
        },
        remove: function(){
            var block = this;

            block.tooltip_catalogCategoryMenu.remove();
            block.tooltip_catalogSubcategoryForm.remove();
            block.tooltip_catalogSubcategoryMenu.remove();

            Editor.prototype.remove.call(block);
        },
        findElements: function(){
            var block = this;

            block.$productList = block.$('.catalogCategory__productList');
            block.$addProductLink = block.$('.catalogCategory__addProductLink');
        },
        'set:editMode': function(editMode) {
            Editor.prototype['set:editMode'].apply(this, arguments);
            params.editMode = editMode;
        },
        'set:catalogSubcategoryId': function(catalogSubCategoryId) {
            var block = this;

            block.$el
                .find('.catalogCategory__subcategoryLink_active')
                .removeClass('catalogCategory__subcategoryLink_active');

            block.$productList.css('visibility', 'hidden');

            if (catalogSubCategoryId){
                block.$subCategoryLink_active = block.$el
                    .find('.catalogCategory__subCategoryLink[subCategory_id="' + catalogSubCategoryId + '"]')
                    .addClass('catalogCategory__subCategoryLink_active');

                block.$addProductLink.attr('href', '/products/create?subCategory=' + catalogSubCategoryId);
            }

            if (catalogSubCategoryId && block.catalogSubCategoryId === catalogSubCategoryId) {
                if (!block.catalogProductsCollection.length){
                    block.$productListTitle.html(KIT.text('Нет товаров'));
                    block.$table_products.hide();
                }
                block.$productList.css('visibility', 'visible');
            }

            if (catalogSubCategoryId && block.catalogSubCategoryId !== catalogSubCategoryId) {
                block.catalogProductsCollection.subCategory = catalogSubCategoryId;
                block.$subCategoryLink_active.addClass('preloader_rows');

                block.catalogProductsCollection.fetch({
                    success: function(collection) {
                        if (collection.length) {
                            block.$productListTitle.html(KIT.text('Список товаров'));
                        } else {
                            block.$productListTitle.html(KIT.text('Нет товаров'));
                        }

                        block.$subCategoryLink_active.removeClass('preloader_rows');
                        block.$productList.css('visibility', 'visible');
                    }
                });
            }
        }
    });
});