define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        id: 'form_group',
        groupId: 0,
        template: require('ejs!./template.ejs'),
        model: function(){
            var GroupModel = require('models/group/group');

            return PAGE.collections.groups.get(this.groupId) || new GroupModel;
        },
        collection: function(){
            return PAGE.collections.groups;
        },
        collections: {
            groupProducts: function(){
                return PAGE.collections.groupProducts
            }
        }
    });
});