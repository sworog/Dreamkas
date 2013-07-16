define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        Editor = require('kit/blocks/editor/editor'),
        CatalogSubCategoryModel = require('models/catalogSubCategory'),
        CatalogCategory__subCategoryList = require('blocks/catalogCategory/catalogCategory__subCategoryList'),
        Tooltip_catalogCategoryMenu = require('blocks/tooltip/tooltip_catalogCategoryMenu/tooltip_catalogCategoryMenu'),
        Tooltip_catalogSubCategoryMenu = require('blocks/tooltip/tooltip_catalogSubCategoryMenu/tooltip_catalogSubCategoryMenu'),
        Tooltip_catalogSubCategoryForm = require('blocks/tooltip/tooltip_catalogSubCategoryForm/tooltip_catalogSubCategoryForm'),
        Table_products = require('blocks/table/table_products/table_products'),
        params = require('pages/catalog/params');

    var router = new Backbone.Router();

    return Editor.extend({
        blockName: 'catalogCategory',

        catalogCategoryModel: null,
        catalogSubCategoryId: null,
        catalogSubcategoriesCollection: null,
        catalogProductsCollection: null,

        templates: {
            index: require('tpl!blocks/catalogCategory/templates/index.html'),
            catalogCategory__subCategoryList: require('tpl!blocks/catalogCategory/templates/catalogCategory__subCategoryList.html'),
            catalogCategory__subCategoryItem: require('tpl!blocks/catalogCategory/templates/catalogCategory__subCategoryItem.html')
        },
        events: {
            'click .catalog__editCategoryLink': function(e) {
                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogCategoryMenu.show({
                    $trigger: $target,
                    catalogCategoryModel: block.catalogCategoryModel
                });
            },
            'click .catalog__addSubCategoryLink': function(e) {
                e.preventDefault();

                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogSubCategoryForm.show({
                    $trigger: $target,
                    collection: block.catalogSubcategoriesCollection,
                    model: new CatalogSubCategoryModel({
                        category: block.catalogCategoryModel.id,
                        group: block.catalogCategoryModel.get('group')
                    })
                });
            },
            'click .catalogCategory__subCategoryLink': function(e) {
                e.preventDefault();
                e.stopImmediatePropagation();

                var block = this,
                    $target = $(e.currentTarget);

                router.navigate($target.attr('href'));

                block.set('catalogSubCategoryId', $target.attr('subCategory_id'));
            }
        },
        listeners: {
            catalogCategoryModel: {
                destroy: function() {
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
            block.tooltip_catalogSubCategoryForm = new Tooltip_catalogSubCategoryForm();
            block.tooltip_catalogSubCategoryMenu = new Tooltip_catalogSubCategoryMenu();

            block.table_products = new Table_products({
                el: block.el.getElementsByClassName('table_products'),
                collection: block.catalogProductsCollection
            });

            new CatalogCategory__subCategoryList({
                el: document.getElementById('catalogCategory__subCategoryList'),
                catalogSubcategoriesCollection: block.catalogSubcategoriesCollection
            });

            block.set('catalogSubCategoryId', block.catalogSubCategoryId);
        },
        remove: function() {
            var block = this;

            block.tooltip_catalogCategoryMenu.remove();
            block.tooltip_catalogSubCategoryForm.remove();
            block.tooltip_catalogSubCategoryMenu.remove();

            Editor.prototype.remove.call(block);
        },
        findElements: function() {
            var block = this;

            block.$productList = block.$('.catalogCategory__productList');
            block.$addProductLink = block.$('.catalogCategory__addProductLink');
            block.$productListTitle = block.$('.catalogCategory__productListTitle');
            block.$table_products = block.$('.table_products');
        },
        'set:editMode': function(editMode) {
            Editor.prototype['set:editMode'].apply(this, arguments);
            params.editMode = editMode;
        },
        'set:catalogSubCategoryId': function(catalogSubCategoryId) {
            var block = this;

            block.$el
                .find('.catalogCategory__subCategoryLink_active')
                .removeClass('catalogCategory__subCategoryLink_active');

            if (catalogSubCategoryId) {
                block.$productList.show();

                block.$addProductLink.attr('href', '/products/create?subCategory=' + catalogSubCategoryId);

                block.$subCategoryLink_active = block.$el
                    .find('.catalogCategory__subCategoryLink[subCategory_id="' + catalogSubCategoryId + '"]')
                    .addClass('catalogCategory__subCategoryLink_active');
            } else {
                block.$productList.hide();
            }

            if (catalogSubCategoryId && block.catalogSubCategoryId !== catalogSubCategoryId) {
                block.catalogProductsCollection.subCategory = catalogSubCategoryId;
                block.$table_products.hide();
                block.$subCategoryLink_active.addClass('preloader_rows');

                block.catalogProductsCollection.fetch({
                    success: function(collection) {
                        if (collection.length) {
                            block.$productListTitle.html(KIT.text('Список товаров'));
                            block.$table_products.show();
                        } else {
                            block.$productListTitle.html(KIT.text('Нет товаров'));
                        }

                        block.$subCategoryLink_active.removeClass('preloader_rows');
                    }
                });
            } else if (!block.catalogProductsCollection.length){
                block.$productListTitle.html(KIT.text('Нет товаров'));
                block.$table_products.hide();
            }
        }
    });
});