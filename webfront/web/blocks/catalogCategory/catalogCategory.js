define(function(require) {
    //requirements
    var Editor = require('kit/blocks/editor/editor'),
        CatalogSubcategoryModel = require('models/catalogSubcategory'),
        CatalogCategory__subcategoryList = require('blocks/catalogCategory/catalogCategory__subcategoryList'),
        Tooltip_catalogCategoryMenu = require('blocks/tooltip/tooltip_catalogCategoryMenu/tooltip_catalogCategoryMenu'),
        Tooltip_catalogSubcategoryMenu = require('blocks/tooltip/tooltip_catalogSubcategoryMenu/tooltip_catalogSubcategoryMenu'),
        Tooltip_catalogSubcategoryForm = require('blocks/tooltip/tooltip_catalogSubcategoryForm/tooltip_catalogSubcategoryForm'),
        Table_products = require('blocks/table/table_products/table_products'),
        Form_catalogCategoryProperties = require('blocks/form/form_catalogCategoryProperties/form_catalogCategoryProperties'),
        Form_catalogSubcategoryProperties = require('blocks/form/form_catalogSubcategoryProperties/form_catalogSubcategoryProperties'),
        pageParams = require('pages/catalog/params');

    var router = new Backbone.Router();

    return Editor.extend({
        __name__: 'catalogCategory',

        catalogCategoryModel: null,
        catalogSubcategoryId: null,
        catalogSubcategoryModel: null,
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
            'click .catalog__addSubcategoryLink': function(e) {
                e.preventDefault();

                var block = this,
                    $target = $(e.target);

                block.tooltip_catalogSubcategoryForm.show({
                    $trigger: $target,
                    collection: block.catalogSubcategoriesCollection,
                    model: new CatalogSubcategoryModel({
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
            block.tooltip_catalogSubcategoryForm = new Tooltip_catalogSubcategoryForm();
            block.tooltip_catalogSubcategoryMenu = new Tooltip_catalogSubcategoryMenu();

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

            block.set('catalogSubcategoryId', block.catalogSubcategoryId);
        },
        remove: function() {
            var block = this;

            block.tooltip_catalogCategoryMenu.remove();
            block.tooltip_catalogSubcategoryForm.remove();
            block.tooltip_catalogSubcategoryMenu.remove();

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

            if (block.catalogSubcategoryModel){
                block.$productListTitle.html(LH.modelNode(block.catalogSubcategoryModel, 'name'));
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
        'set:catalogSubcategoryId': function(catalogSubcategoryId) {
            var block = this;

            block.catalogSubcategoryModel = block.catalogSubcategoriesCollection.get(catalogSubcategoryId);
            block.renderProductList();

            block.$el
                .find('.catalogCategory__subcategoryLink_active')
                .removeClass('catalogCategory__subcategoryLink_active');

            if (catalogSubcategoryId){
                block.$subcategoryLink_active = block.$el
                    .find('.catalogCategory__subcategoryLink[subcategory_id="' + catalogSubcategoryId + '"]')
                    .addClass('catalogCategory__subcategoryLink_active');

                block.$addProductLink.attr('href', '/products/create?subcategory=' + catalogSubcategoryId);

                block.form_catalogSubcategoryProperties = block.form_catalogSubcategoryProperties || new Form_catalogSubcategoryProperties({
                    model: block.catalogSubcategoryModel,
                    el: document.getElementById('form_catalogSubcategoryProperties')
                });
            }

            if (catalogSubcategoryId && block.catalogSubcategoryId !== catalogSubcategoryId) {
                block.catalogProductsCollection.subcategory = catalogSubcategoryId;
                block.$subcategoryLink_active.addClass('preloader_rows');

                block.catalogProductsCollection.fetch({
                    success: function() {
                        block.renderProductList();
                        block.$subcategoryLink_active.removeClass('preloader_rows');
                    }
                });

                block.form_catalogSubcategoryProperties.model = block.catalogSubcategoryModel;
                block.form_catalogSubcategoryProperties.render();
            }
        }
    });
});