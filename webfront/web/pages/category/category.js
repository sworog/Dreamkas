define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        exportCatalog = require('kit/exportCatalog/exportCatalog'),
        SubCategoryModel = require('models/subCategory'),
        router = require('router');

    return Page.extend({
        params: {
            edit: '0',
            categoryId: null,
            groupId: null,
            subCategoryId: '0',
            section: '0',
            subSection: 'products'
        },
        events: {
            'click .catalog__subCategoryLink[data-subcategory_id]': function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (e.target.classList.contains('preloader_stripes')) {
                    return;
                }

                var page = this,
                    subCategoryId = e.target.dataset.subcategory_id;

                page.models.subCategory
                    .clear({
                        silent: true
                    })
                    .set('id', subCategoryId);

                page.collections.products.subCategoryId = subCategoryId;

                e.target.classList.add('preloader_stripes');

                Promise.all([page.models.subCategory.fetch(), page.collections.products.fetch()]).then(function() {
                    page.set('params', {
                        subCategoryId: subCategoryId,
                        section: 'subCategory'
                    });
                });
            },
            'click .catalog__exportLink': function(e) {
                e.preventDefault();

                var page = this;

                if (e.target.classList.contains('preloader_stripes')) {
                    return;
                }

                e.target.classList.add('preloader_stripes');

                Promise.resolve(exportCatalog()).then(function() {
                    alert('Выгрузка началась');
                    e.target.classList.remove('preloader_stripes');
                }, function() {
                    alert('Выгрузка невозможна, обратитесь к администратору');
                    e.target.classList.remove('preloader_stripes');
                });
            },
            'click .catalog__editCategoryLink': function(e) {
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_categoryMenu.show({
                    trigger: e.target,
                    model: page.models.category
                });
            },
            'click .catalog__addSubCategoryLink': function(e) {
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_subCategoryForm.show({
                    trigger: e.target,
                    model: new SubCategoryModel({
                        groupId: page.models.group.id,
                        categoryId: page.models.category.id
                    })
                });
            },
            'click .catalog__editSubCategoryLink': function(e) {
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_subCategoryMenu.show({
                    trigger: e.target,
                    model: page.models.category.collections.subCategories.get(e.target.dataset.subcategory_id)
                });
            }
        },
        listeners: {
            'models.category': {
                destroy: function() {
                    router.navigate('/groups/' + PAGE.params.groupId + '?edit=' + PAGE.params.edit);
                }
            }
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_category.ejs')
        },
        models: {
            group: function() {
                var GroupModel = require('models/group'),
                    page = this;

                return new GroupModel({
                    id: page.params.groupId
                });
            },
            category: function() {
                var CategoryModel = require('models/category'),
                    page = this;

                return new CategoryModel({
                    groupId: page.params.groupId,
                    id: page.params.categoryId
                });
            },
            subCategory: function() {
                var page = this;

                return new SubCategoryModel({
                    id: page.params.subCategoryId !== '0' && page.params.subCategoryId,
                    groupId: page.params.groupId,
                    categoryId: page.params.categoryId
                });
            }
        },
        collections: {
            products: function() {
                var page = this,
                    ProductsCollection = require('collections/catalogProducts');

                return new ProductsCollection([], {
                    subCategoryId: page.params.subCategoryId !== '0' && page.params.subCategoryId
                });
            }
        },
        blocks: {
            tooltip_categoryMenu: require('blocks/tooltip/tooltip_categoryMenu/tooltip_categoryMenu'),
            tooltip_subCategoryMenu: require('blocks/tooltip/tooltip_subCategoryMenu/tooltip_subCategoryMenu'),
            form_categorySettings: function() {
                var page = this,
                    Form_categoryProperties = require('blocks/form/form_categorySettings/form_categorySettings');

                return new Form_categoryProperties({
                    model: page.models.category
                });
            },
            form_subCategorySettings: function() {
                var page = this,
                    Form_subCategoryProperties = require('blocks/form/form_subCategorySettings/form_subCategorySettings');

                return new Form_subCategoryProperties({
                    model: page.models.subCategory
                });
            },
            form_subCategory: function() {
                var page = this,
                    Form_subCategory = require('blocks/form/form_subCategory/form_subCategory');

                return new Form_subCategory({
                    groupId: page.models.group.id,
                    categoryId: page.models.category.id,
                    collection: page.models.category.collections.subCategories
                });
            }
        }
    });
});