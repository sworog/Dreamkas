define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        exportCatalog = require('kit/exportCatalog/exportCatalog'),
        CategoryModel = require('models/category'),
        router = require('router');

    return Page.extend({
        params: {
            edit: '0',
            groupId: null,
            section: 'categories'
        },
        events: {
            'click .catalog__exportLink': function(e) {
                e.preventDefault();

                var page = this;

                if (e.target.classList.contains('preloader_stripes')){
                    return;
                }

                e.target.classList.add('preloader_stripes');

                Promise.resolve(exportCatalog()).then(function(){
                    alert('Выгрузка началась');
                    e.target.classList.remove('preloader_stripes');
                }, function(){
                    alert('Выгрузка невозможна, обратитесь к администратору');
                    e.target.classList.remove('preloader_stripes');
                });
            },
            'click .catalog__editGroupLink': function(e){
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_groupMenu.show({
                    trigger: e.target,
                    model: page.models.group
                });
            },
            'click .catalog__addCategoryLink': function(e) {
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_categoryForm.show({
                    trigger: e.target,
                    model: new CategoryModel({
                        groupId: page.models.group.id
                    })
                });
            },
            'click .catalog__editCategoryLink': function(e){
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_categoryMenu.show({
                    trigger: e.target,
                    model: page.models.group.collections.categories.get(e.target.dataset.category_id)
                });
            }
        },
        listeners: {
            'models.group': {
                destroy: function(){
                    router.navigate('/catalog?edit=' + PAGE.params.edit);
                }
            }
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_group.ejs')
        },
        models: {
            group: function(){
                var GroupModel = require('models/group'),
                    page = this;

                return new GroupModel({
                    id: page.params.groupId
                });
            }
        },
        blocks: {
            tooltip_groupMenu: require('blocks/tooltip/tooltip_groupMenu/tooltip_groupMenu'),
            tooltip_categoryMenu: require('blocks/tooltip/tooltip_categoryMenu/tooltip_categoryMenu'),
            form_groupSettings: function(){
                var page = this,
                    Form_groupProperties = require('blocks/form/form_groupSettings/form_groupSettings');

                return new Form_groupProperties({
                    model: page.models.group
                });
            },
            form_category: function(){
                var page = this,
                    Form_category = require('blocks/form/form_category/form_category');

                return new Form_category({
                    groupId: page.models.group.id,
                    collection: page.models.group.collections.categories
                });
            }
        }
    });
});