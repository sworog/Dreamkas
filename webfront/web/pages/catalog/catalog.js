define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page.deprecated');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'catalog',
        collections: {
            groups: function() {
                var page = this,
                    GroupsCollection = require('collections/groups/groups'),
                    groupsCollection = new GroupsCollection();

                groupsCollection.on({
                    add: function(groupModel) {

                        var modal = $('.modal:visible');

                        page.models.group = groupModel;

                        modal.one('hidden.bs.modal', function(e) {
                            page.render();
                        });

                        modal.modal('hide');
                    }
                });

                return groupsCollection;
            }
        },
        blocks: {
            form_groupAdd: function() {
                var page = this,
                    Form_group = require('blocks/form/form_group/form_group');

                return new Form_group({
                    collection: page.collections.groups,
                    el: document.getElementById('form_groupAdd')
                });
            }
        }
    });
});