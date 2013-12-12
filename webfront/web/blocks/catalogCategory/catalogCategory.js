define(function(require) {
    //requirements
    var Editor = require('kit/blocks/editor/editor'),
        CatalogSubCategoryModel = require('models/catalogSubCategory'),
        CatalogCategory__subcategoryList = require('blocks/catalogCategory/catalogCategory__subcategoryList'),
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
        catalogSubcategoriesCollection: null,
        catalogProductsCollection: null,

        template: require('tpl!blocks/catalogCategory/templates/index.html'),
        templates: {
            index: require('tpl!blocks/catalogCategory/templates/index.html'),
            catalogCategory__subcategoryList: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryList.html'),
            catalogCategory__subcategoryItem: require('tpl!blocks/catalogCategory/templates/catalogCategory__subcategoryItem.html')
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
                        group: block.catalogCategoryModel.get('group'),
                        retailMarkupMax: block.catalogCategoryModel.get('retailMarkupMax'),
                        retailMarkupMin: block.catalogCategoryModel.get('retailMarkupMin')
                    })
                });
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

            new Form_catalogCategoryProperties({
                model: block.catalogCategoryModel,
                el: document.getElementById('form_catalogCategoryProperties')
            });

            new CatalogCategory__subcategoryList({
                el: document.getElementById('catalogCategory__subcategoryList'),
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
            block.$productListNotification = block.$('.catalogCategory__productListNotification');
        },
        renderProductList: function(){
            var block = this;

            if (block.catalogSubCategoryModel){
                block.$productListTitle.html(LH.modelNode(block.catalogSubCategoryModel, 'name'));
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

            block.catalogSubCategoryModel = block.catalogSubcategoriesCollection.get(catalogSubCategoryId);
            block.renderProductList();

            block.$el
                .find('.catalogCategory__subcategoryLink_active')
                .removeClass('catalogCategory__subcategoryLink_active');

            if (catalogSubCategoryId){
                block.$subcategoryLink_active = block.$el
                    .find('.catalogCategory__subcategoryLink[subcategory_id="' + catalogSubCategoryId + '"]')
                    .addClass('catalogCategory__subcategoryLink_active');

                block.$addProductLink.attr('href', '/products/create?subcategory=' + catalogSubCategoryId);

                block.form_catalogSubCategoryProperties = block.form_catalogSubCategoryProperties || new Form_catalogSubCategoryProperties({
                    model: block.catalogSubCategoryModel,
                    el: document.getElementById('form_catalogSubCategoryProperties')
                });
            }

            if (catalogSubCategoryId && block.catalogSubCategoryId !== catalogSubCategoryId) {
                block.catalogProductsCollection.subcategory = catalogSubCategoryId;
                block.$subcategoryLink_active.addClass('preloader_rows');

                block.catalogProductsCollection.fetch({
                    success: function() {
                        block.renderProductList();
                        block.$subcategoryLink_active.removeClass('preloader_rows');
                    }
                });

                block.form_catalogSubCategoryProperties.model = block.catalogSubCategoryModel;
                block.form_catalogSubCategoryProperties.render();
            }
        }
    });
});