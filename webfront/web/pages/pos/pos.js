define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        collections: {
            stores: require('collections/stores/stores')
        },
        blocks: {
            select_stores: require('blocks/select/stores/stores')
        }
    });
});