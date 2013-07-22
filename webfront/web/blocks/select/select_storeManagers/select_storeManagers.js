define(function(require) {
    //requirements
    var Select = require('kit/blocks/select/select');

    return Select.extend({
        blockName: 'select_storeManagers',
        storeManagerCandidatesCollection: null,
        templates: {
            index: require('tpl!blocks/select/select_storeManagers/templates/index.html')
        },
        listeners: {
            storeManagerCandidatesCollection: {
                remove: function(model, collectoin, options){
                    var block = this;

                    block.$el.find('option[value="' + model.url() + '"]').remove();
                    block.$el.prop('selectedIndex',0);

                    if (!collectoin.length){
                        block.$el.hide();
                    }
                },
                'add': function(model, collectoin, options){
                    var block = this;

                    block.render();
                }
            }
        }
    });
});