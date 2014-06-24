define(function(require) {
    //requirements
    var Page = require('kit/page'),
        GroupModel = require('models/group'),
        exportCatalog = require('kit/exportCatalog');

    return Page.extend({
        params: {
            edit: '0'
        },
        events: {
            'click .catalog__addGroupLink': function(e) {
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_groupForm.show({
                    trigger: e.target,
                    model: new GroupModel()
                });
            },
            'click .catalog__exportLink': function(e) {
                e.preventDefault();

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
                    model: page.collections.groups.get(e.target.dataset.group_id)
                });
            },
            'click .catalog__editCategoryLink': function(e){
                e.preventDefault();

                var page = this;

                page.blocks.tooltip_categoryMenu.show({
                    trigger: e.target,
                    model: page.collections.groups.get(e.target.dataset.group_id).collections.categories.get(e.target.dataset.category_id)
                });
            }
        },
        listeners: {
            'change:params.edit': function(){
                var page = this;

                page.render();
            }
        },
        partials: {
            content: require('tpl!./content.ejs'),
            localNavigation: require('tpl!blocks/localNavigation/localNavigation_catalog.ejs')
        },
        collections: {
            groups: require('collections/groups')
        },
        blocks: {
            form_group: function(){
                var page = this,
                    Form_group = require('blocks/form/form_group/form_group');

                return new Form_group({
                    collection: page.collections.groups
                });
            },
            tooltip_groupMenu: require('blocks/tooltip/tooltip_groupMenu/tooltip_groupMenu'),
            tooltip_categoryMenu: require('blocks/tooltip/tooltip_categoryMenu/tooltip_categoryMenu')
        }
    });
});