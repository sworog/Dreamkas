define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        GroupModel = require('models/group/group'),
        Form_group = require('blocks/form/form_group/form_group'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        title: 'Ассортимент',
        activeNavigationItem: 'catalog',
        collections: {
            groups: function() {
                var GroupsCollection = require('collections/groups/groups');

                return new GroupsCollection();
            }
        },
        events: {
            'click .groupList__link': function(e){

                if (e.target.classList.contains('loading') || $(e.target).closest('li.active').length){
                    e.stopPropagation();
                } else {
                    e.target.classList.add('loading');
                }

            }
        },
        models: {
            group: null
        },
        blocks: {
            form_groupAdd: function(){
                var page = this;

                return new Form_group({
                    collection: page.collections.groups,
                    el: document.getElementById('form_groupAdd')
                });
            },
            form_groupEdit: function(){
                var page = this;

                return new Form_group({
                    model: page.models.group,
                    el: document.getElementById('form_groupEdit') || undefined
                });
            }
        },
        initialize: function(){
            var page = this;

            Page.prototype.initialize.apply(page, arguments);

            page.collections.groups.on({
                add: function(groupModel){

                    page.models.group = groupModel;

                    router.navigate('/catalog/groups/' + groupModel.id, {
                        trigger: false
                    });

                },
                remove: function(){

                    page.models.group = page.collections.groups.at(0) || new GroupModel();

                    router.navigate('/catalog', {
                        trigger: false
                    });
                }
            });
        },
        fetch: function(){
            var page = this;

            return Page.prototype.fetch.apply(page, arguments).then(function(){
                page.models.group = page.collections.groups.get(page.params.groupId) || page.collections.groups.at(0) || new GroupModel();
            });
        }
    });
});