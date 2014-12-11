define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        id: 'form_group',
        groupId: 0,
        template: require('ejs!./template.ejs'),
        blocks: {
            popover: require('blocks/popover/popover'),
            removeButton: function(){

                var block = this,
                    RemoveButton = require('blocks/removeButton/removeButton');

                return new RemoveButton({
                    model: block.model,
                    removeText: 'Удалить группу'
                });
            }
        },
        model: function(){
            var GroupModel = require('resources/group/model');

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