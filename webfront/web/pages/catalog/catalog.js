define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'catalog',
        collections: {
            groups: require('resources/group/collection')
        },
        blocks: {
            modal_group: require('blocks/modal/group/group'),
            groupList: require('blocks/groupList/groupList')
        }
    });
});