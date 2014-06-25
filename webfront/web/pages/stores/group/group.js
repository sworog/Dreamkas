define(function(require, exports, module) {
    //requirements
    var Page = require('pages/store');

    return Page.extend({
        params: {
            storeId: null,
            groupId: null
        },
        partials: {
            content: require('ejs!./content.ejs')
        },
        models: {
            group: function(){
                var GroupModel = require('models/group'),
                    page = this;

                return new GroupModel({
                    id: page.params.groupId,
                    storeId: page.params.storeId
                });
            }
        }
    });
});