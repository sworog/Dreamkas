define(function(require) {
    //requirements
    var Select = require('kit/blocks/select/select');

    return Select.extend({
        blockName: 'select_storeManagers',
        storeManagersCollection: null,
        templates: {
            index: require('tpl!blocks/select/select_storeManagers/templates/index.html')
        }
    });
});