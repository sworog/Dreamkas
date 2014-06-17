define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page'),
        exportCatalog = require('kit/exportCatalog'),
        router = require('router');

    return Page.extend({
        params: {
            edit: '0',
            categoryId: null,
            subCategoryId: null,
            groupId: null,
            section: 'subCategories'
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
            'click .catalog__editCategoryLink': function(e){
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_categoryMenu.show({
                    trigger: e.target,
                    model: page.models.category
                });
            }
        },
        listeners: {
            'change:params.edit': function(){
                var page = this;

                page.render();
            },
            'change:params.section': function(section){
                var page = this;

                page.el.querySelector('.content').setAttribute('section', section);
                page.el.querySelector('input[type="text"]').focus();
            },
            'models.category': {
                destroy: function(){
                    router.navigate('/groups/' + PAGE.params.groupId + '?edit=' + PAGE.params.edit);
                }
            }
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_category.ejs')
        },
        models: {
            group: function(){
                var GroupModel = require('models/group'),
                    page = this;

                return new GroupModel({
                    id: page.params.groupId
                });
            },
            category: function(){
                var CategoryModel = require('models/category'),
                    page = this;

                return new CategoryModel({
                    groupId: page.params.groupId,
                    id: page.params.categoryId
                });
            }
        },
        blocks: {
            tooltip_categoryMenu: require('blocks/tooltip/tooltip_categoryMenu/tooltip_categoryMenu'),
            //tooltip_subCategoryForm: require('blocks/tooltip/tooltip_subCategoryForm/tooltip_subCategoryForm'),
            form_categoryProperties: function(){
                var page = this,
                    Form_categoryProperties = require('blocks/form/form_categoryProperties/form_categoryProperties');

                return new Form_categoryProperties({
                    model: page.models.category
                });
            }
        }
    });
});