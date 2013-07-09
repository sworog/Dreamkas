define(function(require) {
    //requirements
    var Block = require('kit/block');

    return Block.extend({
        blockName: 'catalog__groupNavigation',
        catalogGroupsCollection: null,
        currentGroupModel: null,
        templates: {
            index: require('tpl!blocks/catalog/templates/catalog__groupNavigation.html')
        }
    });
});