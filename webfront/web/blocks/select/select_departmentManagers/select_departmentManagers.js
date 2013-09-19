define(function(require) {
    //requirements
    var Select = require('kit/blocks/select/select');

    return Select.extend({
        __name__: 'select_departmentManagers',
        departmentManagerCandidatesCollection: null,
        departmentManagersCollection: null,
        storeModel: null,
        template: require('tpl!blocks/select/select_departmentManagers/templates/index.html'),
        events: {
            'change': function(event) {
                var block = this,
                    userId = block.$el.find(':selected').data('user_id'),
                    userModel = block.departmentManagerCandidatesCollection.get(userId);

                block.$el.addClass('preloader_rows');

                block.storeModel.linkDepartmentManager(block.$el.val()).done(function(){
                    block.$el.removeClass('preloader_rows');
                    block.departmentManagerCandidatesCollection.remove(userModel);
                    block.departmentManagersCollection.add(userModel);
                });
            }
        },
        listeners: {
            departmentManagerCandidatesCollection: {
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
        },
        initialize: function(){
            var block = this;

            Select.prototype.initialize.apply(block, arguments);

            if (!block.departmentManagerCandidatesCollection.length){
                block.$el.hide();
            }
        }
    });
});