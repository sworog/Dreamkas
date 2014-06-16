define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        exportCatalog = require('kit/exportCatalog'),
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
            'click .group__editLink': function(e){
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_groupMenu.show({
                    trigger: e.target,
                    model: page.models.group
                });
            },
            'click .group__addCategoryLink': function(e) {
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_categoryForm.show({
                    trigger: e.target,
                    collection: page.models.group.collections.categories,
                    model: new CategoryModel({
                        group: {
                            id: page.models.group.id
                        }
                    })
                });
            }
        },
        listeners: {
            'change:params.edit': function(){
                var page = this;

                page.render();
            },
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
            tooltip_categoryForm: function(){
                var Tooltip_categoryForm = require('blocks/tooltip/tooltip_categoryForm/tooltip_categoryForm');

                return new Tooltip_categoryForm({
                    newCategory: true
                });
            },
            tooltip_groupMenu: require('blocks/tooltip/tooltip_groupMenu/tooltip_groupMenu')
        }
    });
});