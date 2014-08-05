define(function(require) {
    //requirements
    var Block = require('kit/core/block.deprecated'),
        Select_storeManagers = require('blocks/select/select_storeManagers/select_storeManagers');

    return Block.extend({
        __name__: 'store__storeManagers',
        storeManagerCandidatesCollection: null,
        storeManagersCollection: null,
        storeModel: null,
        template: require('ejs!blocks/store/store__storeManagers.html'),
        templates: {
            store__storeManagerItem: require('ejs!blocks/store/store__storeManagerItem.html')
        },
        events: {
            "click .store__storeManagerRemoveLink": function(event) {
                event.stopPropagation();
                var block = this,
                    $link = $(event.target),
                    $item = $link.closest('.store__storeManagerItem'),
                    userId = $link.data('user_id'),
                    userModel = block.storeManagersCollection.get(userId);

                $item.addClass('preloader_rows');

                block.storeModel.unlinkStoreManager(userModel.url()).done(function(){
                    $item.removeClass('preloader_rows');
                    block.storeManagersCollection.remove(userModel);
                    block.storeManagerCandidatesCollection.add(userModel);
                });
            }
        },
        listeners: {
            storeManagersCollection: {
                remove: function(storeManagerModel) {
                    var block = this;

                    block.$('span[model-id="' + storeManagerModel.id + '"]').closest(".store__storeManagerItem").remove();
                },
                add: function(storeManagerModel) {
                    var block = this;

                    block.$managerList.append(block.templates.store__storeManagerItem({
                        storeManagerModel: storeManagerModel
                    }));
                }
            },
            storeManagerCandidatesCollection: {
                remove: function(){
                    var block = this;

                    if (!block.storeManagerCandidatesCollection.length){
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

            block.select_storeManagers = new Select_storeManagers({
                storeManagerCandidatesCollection: block.storeManagerCandidatesCollection,
                storeManagersCollection: block.storeManagersCollection,
                storeModel: block.storeModel,
                el: document.getElementById('select_storeManagers')
            });

            if (!block.storeManagerCandidatesCollection.length){
                block.$managersNotification.show();
            }
        },
        findElements: function(){
            var block = this;

            block.$managerList = block.$('.store__managerList');
            block.$managersNotification = block.$('.store__managersNotification');
        }
    });
});