define(function(require) {
    //requirements
    var Select = require('kit/blocks/select/select');

    return Select.extend({
        blockName: 'select_storeManagers',
        storeManagerCandidatesCollection: null,
        storeManagersCollection: null,
        storeModel: null,
        templates: {
            index: require('tpl!blocks/select/select_storeManagers/templates/index.html')
        },
        events: {
            'change': function(event) {
                var block = this,
                    userId = block.$el.find(':selected').data('user_id'),
                    userModel = block.storeManagerCandidatesCollection.get(userId);

                block.storeModel.linkManager(block.$el.val()).done(function(){
                    block.storeManagerCandidatesCollection.remove(userModel);
                    block.storeManagersCollection.add(userModel);
                });
            }
        },
        listeners: {
            storeManagerCandidatesCollection: {
                remove: function(model, collectoin, options){
                    var block = this;

                    block.render();
                    block.$el.prop('selectedIndex',0);

                    if (!collectoin.length){
                        block.$el.hide();
                    }
                },
                'add': function(model, collectoin, options){
                    var block = this;

                    block.render();
                    block.$el.prop('selectedIndex',0);
                    block.$el.show();
                }
            }
        }
    });
});