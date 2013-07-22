define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Select_storeManagers = require('blocks/select/select_storeManagers/select_storeManagers');

    return Block.extend({
        blockName: 'store__managers',
        storeManagerCandidatesCollection: null,
        storeManagersCollection: null,
        storeModel: null,
        templates: {
            index: require('tpl!blocks/store/templates/store__managers.html'),
            store__managerItem: require('tpl!blocks/store/templates/store__managerItem.html')
        },
        events: {
            'change #select_storeManagers': function(event) {
                var block = this,
                    $select = $(event.target);

                block.storeModel.linkManager($select.val()).done(function(){
                    var userId = $select.find(':selected').data('user_id'),
                        userModel = block.storeManagerCandidatesCollection.get(userId);

                    block.storeManagerCandidatesCollection.remove(userModel);
                    block.storeManagersCollection.add(userModel);
                });
            },
            'click .store__managerItemRemove': function(event) {
                var block = this,
                    $link = $(event.target),
                    userModel = block.storeManagersCollection.get($link.data('user_id'));

                block.storeModel.unlinkManager(userModel.url()).done(function(){
                    block.storeManagersCollection.remove(userModel);
                    block.storeManagerCandidatesCollection.add(userModel);
                });
            }
        },
        listeners: {
            storeManagersCollection: {
                'remove': function(storeManagerModel) {
                    var block = this;

                    block.$('span[model_id="' + storeManagerModel.id + '"]').closest(".store__managerItem").remove();
                },
                'add': function(storeManagerModel) {
                    var block = this;

                    block.$managerList.append(block.templates.store__managerItem({
                        storeManagerModel: storeManagerModel
                    }));
                }
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.select_storeManagers = new Select_storeManagers({
                storeManagerCandidatesCollection: block.storeManagerCandidatesCollection,
                el: document.getElementById('select_storeManagers')
            });
        },
        findElements: function(){
            var block = this;

            block.$managerList = block.$('.store__managerList');
        }
    });
});