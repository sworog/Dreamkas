define(function(require) {
    //requirements
    var Select = require('kit/blocks/select/select');

    return Select.extend({
        blockName: 'select_storeManagers',
        storeManagersCollection: null,
        templates: {
            index: require('tpl!blocks/select/select_storeManagers/templates/index.html')
        },
        listeners: {
            storeManagersCollection: {
                remove: function(model, collectoin, options){
                    var block = this;

                    block.$el.find('option[value="' + model.url() + '"]').remove();
                    block.$el.prop('selectedIndex',0);

                    if (!collectoin.length){
                        block.$el.hide();
                    }
                }
            }
        }
    });
});