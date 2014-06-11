define(function(require, exports, module) {
    //requirements
    var Page = require('pages/catalog/catalog');

    return Page.extend({
        params: {
            groupId: null,
            section: 'categories'
        },
        partials: {
            content: require('tpl!./content.ejs')
        },
        models: {
            group: function(){
                var GroupModel = require('models/group'),
                    page = this;

                return new GroupModel({
                    id: page.params.groupId
                });
            }
        }
    });
});