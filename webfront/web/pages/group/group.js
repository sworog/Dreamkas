define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page'),
        router = require('router');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'catalog',
        collections: {
            groups: function() {
                var GroupsCollection = require('collections/groups/groups');

                return new GroupsCollection();
            }
        },
        models: {
            group: function(){
                var page = this,
                    GroupModel = require('models/group/group'),
                    groupModel = new GroupModel({
                        id: page.params.groupId
                    });

                groupModel.on({
                    change: function(){
                        var modal = $('.modal:visible');

                        modal.one('hidden.bs.modal', function(e) {
                            page.render();
                        });

                        modal.modal('hide');
                    },
                    destroy: function(){
                        var modal = $('.modal:visible');

                        modal.one('hidden.bs.modal', function(e) {
                            router.navigate('/catalog');
                        });

                        modal.modal('hide');
                    }
                });

                return groupModel;
            }
        },
        blocks: {
            form_groupEdit: function() {
                var page = this,
                    Form_group = require('blocks/form/form_group/form_group'),
                    form_group = new Form_group({
                        model: page.models.group,
                        el: document.getElementById('form_groupEdit')
                    });

                form_group.on('submit:success', function(){
                    page.models.group.trigger('change');
                });

                return form_group;
            }
        }
    });
});