define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Select_storeManagers = require('blocks/select/select_storeManagers/select_storeManagers');

    return Block.extend({
        blockName: 'store__managers',
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
                        userModel = block.storeManagersCollection.get(userId);

                    block.$managerList.append(block.templates.store__managerItem({
                        storeManagerModel: userModel
                    }));

                    block.storeManagersCollection.remove(userModel);
                });
            }
        },
        initialize: function(){
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.select_storeManagers = new Select_storeManagers({
                storeManagersCollection: block.storeManagersCollection,
                el: document.getElementById('select_storeManagers')
            });
        },
        findElements: function(){
            var block = this;

            block.$managerList = block.$('.store__managerList');
        }
    });
});