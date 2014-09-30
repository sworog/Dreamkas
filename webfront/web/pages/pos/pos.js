define(function(require, exports, module) {
    //requirements
    var Page = require('blocks/page/page');

    return Page.extend({
        template: require('ejs!./template.ejs'),
        events: {
            'change select[name="store"]': function(e){
                var page = this;

                page.$('[href^="/pos/stores/"]').attr('href', '/pos/stores/' + e.target.value);
            }
        },
        collections: {
            stores: require('collections/stores/stores')
        },
        blocks: {
            select_stores: require('blocks/select/store/store')
        }
    });
});