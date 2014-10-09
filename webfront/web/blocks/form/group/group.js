define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        id: 'form_group',
        groupId: 0,
        template: require('ejs!./template.ejs'),
        events: {
            'click .form_group__removeLink': function(e) {
                var block = this;

                e.target.classList.add('loading');

                block.model.destroy().then(function() {
                    e.target.classList.remove('loading');
                });
            }
        },
        blocks: {
            popover: require('blocks/popover/popover')
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