define(function(require, exports, module) {
    //requirements
    var Page = require('kit/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        title: 'Ассортимент',
        activeNavigationItem: 'catalog',
        collections: {
            groups: function() {
                var GroupsCollection = require('collections/groups/groups');

                return new GroupsCollection();
            }
        }
    });
});