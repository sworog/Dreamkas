define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        content: require('ejs!./content.ejs'),
        activeNavigationItem: 'dashboard',
        collections: {
            stores: require('resources/store/collection')
        },
        blocks: {
            steps: require('./steps'),
            info: require('./info')
        }
    });
});
