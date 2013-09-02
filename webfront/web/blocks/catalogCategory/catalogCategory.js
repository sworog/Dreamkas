define(function(require) {
    //requirements
    var Editor = require('kit/blocks/editor/editor'),
        CatalogSubCategoryModel = require('models/catalogSubCategory'),
        CatalogCategory__subCategoryList = require('blocks/catalogCategory/catalogCategory__subCategoryList'),
        Tooltip_catalogCategoryMenu = require('blocks/tooltip/tooltip_catalogCategoryMenu/tooltip_catalogCategoryMenu'),
        Tooltip_catalogSubCategoryMenu = require('blocks/tooltip/tooltip_catalogSubCategoryMenu/tooltip_catalogSubCategoryMenu'),
        Tooltip_catalogSubCategoryForm = require('blocks/tooltip/tooltip_catalogSubCategoryForm/tooltip_catalogSubCategoryForm'),
        Table_products = require('blocks/table/table_products/table_products'),
        Form_catalogCategoryProperties = require('blocks/form/form_catalogCategoryProperties/form_catalogCategoryProperties'),
        Form_catalogSubCategoryProperties = require('blocks/form/form_catalogSubCategoryProperties/form_catalogSubCategoryProperties'),
        pageParams = require('pages/catalog/params');

    var router = new Backbone.Router();

    return Editor.extend({
        __name__: 'catalogCategory',

        catalogCategoryModel: null,
        catalogSubCategoryId: null,
        catalogSubCategoryModel: null,
        catalogSubCategoriesCollection: null,
        catalogProductsCollection: null,

        templates: {
            index: require('tpl!blocks/catalogCategory/templates/index.html'),
            catalogCategory__subCategoryList: require('tpl!blocks/catalogCategory/templates/catalogCategory__subCategoryList.html'),
            catalogCategory__subCategoryItem: require('tpl!blocks/catalogCategory/templates/catalogCategory__subCategoryItem.html')
        },
        events: {
            'click .catalog__editCategoryLink': 'click .catalog__editCategoryLink',
            'click .catalog__addSubCategoryLink': 'click .catalog__addSubCategoryLink',
            'click .catalogCategory__subCategoryLink': 'click .catalogCategory__subCategoryLink'
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
                collection: block.catalogSubCategoriesCollection,
                model: new CatalogSubCategoryModel({
                    category: block.catalogCategoryModel.id,
                    group: block.catalogCategoryModel.get('group'),
                    retailMarkupMax: block.catalogCategoryModel.get('retailMarkupMax'),
                    retailMarkupMin: block.catalogCategoryModel.get('retailMarkupMin')
                })
            });
        },
        'click .catalogCategory__subCategoryLink': function(e) {
            e.preventDefault();
            e.stopImmediatePropagation();

            var block = this,
                $target = $(e.currentTarget);

            router.navigate(router.toFragment($target.attr('href'), {
                editMode: pageParams.editMode,
                storeId: pageParams.storeId
            }));

            block.set('catalogSubCategoryId', $target.attr('subCategory_id'));
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

            new Form_catalogCategoryProperties({
                model: block.catalogCategoryModel,
                el: document.getElementById('form_catalogCategoryProperties')
            });

            new CatalogCategory__subCategoryList({
                el: document.getElementById('catalogCategory__subCategoryList'),
                catalogSubCategoriesCollection: block.catalogSubCategoriesCollection
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
            block.$productListNotification = block.$('.catalogCategory__productListNotification');
        },
        renderProductList: function(){
            var block = this;

            if (block.catalogSubCategoryModel){
                block.$productListTitle.html(LH.attr(block.catalogSubCategoryModel, 'name'));
                block.$productList.show();
            } else {
                block.$productList.hide();
            }

            if (block.catalogProductsCollection.length) {
                block.$table_products.show();
                block.$productListNotification.hide();
            } else {
                block.$table_products.hide();
                block.$productListNotification.show();
            }
        },
        'set:editMode': function(editMode) {
            Editor.prototype['set:editMode'].apply(this, arguments);
            pageParams.editMode = editMode;
        },
        'set:catalogSubCategoryId': function(catalogSubCategoryId) {
            var block = this;

            block.catalogSubCategoryModel = block.catalogSubCategoriesCollection.get(catalogSubCategoryId);
            block.renderProductList();

            block.$el
                .find('.catalogCategory__subCategoryLink_active')
                .removeClass('catalogCategory__subCategoryLink_active');

            if (catalogSubCategoryId){
                block.$subCategoryLink_active = block.$el
                    .find('.catalogCategory__subCategoryLink[subCategory_id="' + catalogSubCategoryId + '"]')
                    .addClass('catalogCategory__subCategoryLink_active');

                block.$addProductLink.attr('href', '/products/create?subCategory=' + catalogSubCategoryId);

                block.form_catalogSubCategoryProperties = block.form_catalogSubCategoryProperties || new Form_catalogSubCategoryProperties({
                    model: block.catalogSubCategoryModel,
                    el: document.getElementById('form_catalogSubCategoryProperties')
                });
            }

            if (catalogSubCategoryId && block.catalogSubCategoryId !== catalogSubCategoryId) {
                block.catalogProductsCollection.subCategory = catalogSubCategoryId;
                block.$subCategoryLink_active.addClass('preloader_rows');

                block.catalogProductsCollection.fetch({
                    success: function() {
                        block.renderProductList();
                        block.$subCategoryLink_active.removeClass('preloader_rows');
                    }
                });

                block.form_catalogSubCategoryProperties.model = block.catalogSubCategoryModel;
                block.form_catalogSubCategoryProperties.render();
            }
        }
    });
});