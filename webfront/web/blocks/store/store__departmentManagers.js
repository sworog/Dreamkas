define(function(require) {
    //requirements
    var Block = require('kit/core/block'),
        Select_departmentManagers = require('blocks/select/select_departmentManagers/select_departmentManagers');

    return Block.extend({
        __name__: 'department__managers',
        departmentManagerCandidatesCollection: null,
        departmentManagersCollection: null,
        storeModel: null,
        template: require('tpl!blocks/store/store__departmentManagers.html'),
        templates: {
            store__departmentManagerItem: require('tpl!blocks/store/store__departmentManagerItem.html')
        },
        events: {
            "click .store__departmentManagerRemoveLink": function(event) {
                event.stopPropagation();
                var block = this,
                    $link = $(event.target),
                    $item = $link.closest('.store__departmentManagerItem'),
                    userId = $link.data('user_id'),
                    userModel = block.departmentManagersCollection.get(userId);

                $item.addClass('preloader_rows');

                block.storeModel.unlinkDepartmentManager(userModel.url()).done(function(){
                    $item.removeClass('preloader_rows');
                    block.departmentManagersCollection.remove(userModel);
                    block.departmentManagerCandidatesCollection.add(userModel);
                });
            }
        },
        listeners: {
            departmentManagersCollection: {
                remove: function(departmentManagerModel) {
                    var block = this;

                    block.$('span[model-id="' + departmentManagerModel.id + '"]').closest(".store__departmentManagerItem").remove();
                },
                add: function(departmentManagerModel) {
                    var block = this;

                    block.$managerList.append(block.templates.store__departmentManagerItem({
                        departmentManagerModel: departmentManagerModel
                    }));
                }
            },
            departmentManagerCandidatesCollection: {
                remove: function(){
                    var block = this;

                    if (!block.departmentManagerCandidatesCollection.length){
                        block.$managersNotification.show();
                    }
                },
                add: function(){
                    var block = this;

                    block.$managersNotification.hide();
                }
            }
        },
        initialize: function(){
            var block = this;

            block.select_departmentManagers = new Select_departmentManagers({
                departmentManagerCandidatesCollection: block.departmentManagerCandidatesCollection,
                departmentManagersCollection: block.departmentManagersCollection,
                storeModel: block.storeModel,
                el: document.getElementById('select_departmentManagers')
            });

            if (!block.departmentManagerCandidatesCollection.length){
                block.$managersNotification.show();
            }
        },
        findElements: function(){
            var block = this;

            block.$managerList = block.$('.store__departmentManagerList');
            block.$managersNotification = block.$('.store__managersNotification');
        }
    });
});