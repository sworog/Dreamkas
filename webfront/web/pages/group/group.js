define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        GroupModel = require('models/group/group'),
        Form_group = require('blocks/form/form_group/form_group'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'catalog',
        collections: {
            groups: function() {
                var page = this,
                    GroupsCollection = require('collections/groups/groups'),
                    groupsCollection = new GroupsCollection();

                groupsCollection.on({
                    change: function() {

                        var modal = $('.modal:visible');

                        modal.modal('hide');

                        modal.one('hidden.bs.modal', function(e) {
                            page.render();
                        });

                    },
                    add: function(groupModel) {

                        var modal = $('.modal:visible');

                        modal.modal('hide');

                        modal.one('hidden.bs.modal', function(e) {
                            page.setGroup(groupModel);
                        });

                    },
                    remove: function() {

                        var modal = $('.modal:visible');

                        modal.modal('hide');

                        modal.one('hidden.bs.modal', function(e) {
                            page.setGroup(page.collections.groups.at(0));
                        });
                    }
                });

                return groupsCollection;
            }
        },
        events: {
            'click .groupList__link': function(e) {

                e.preventDefault();
                e.stopPropagation();

                var page = this,
                    groupModel;

                if (!(e.target.classList.contains('loading') || $(e.target).closest('li.active').length)) {

                    e.target.classList.add('loading');

                    groupModel = page.collections.groups.get(e.target.dataset.groupid);

                    page.setGroup(groupModel);
                }
            }
        },
        models: {
            group: null
        },
        blocks: {
            form_groupAdd: function() {
                var page = this;

                return new Form_group({
                    collection: page.collections.groups,
                    el: document.getElementById('form_groupAdd')
                });
            },
            form_groupEdit: function() {
                var page = this;

                return new Form_group({
                    model: page.models.group,
                    el: document.getElementById('form_groupEdit') || undefined
                });
            }
        },
        fetch: function() {
            var page = this;

            return Page.prototype.fetch.apply(page, arguments).then(function() {
                page.models.group = page.collections.groups.get(page.params.groupId) || page.collections.groups.at(0) || new GroupModel();
            });
        },
        setGroup: function(groupModel) {
            var page = this;

            page.models.group = groupModel || new GroupModel();

            router.navigate(page.models.group.id ? '/catalog/groups/' + page.models.group.id : '/catalog', {
                trigger: false
            });

            page.render();
        }
    });
});