define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        id: 'modal_group',
        template: require('ejs!./template.ejs'),
        groupId: null,
        models: {
            group: null
        },
        render: function() {
            var GroupModel = require('models/group/group');

            this.models.group = PAGE.models.group || PAGE.collections.groups.get(this.groupId) || new GroupModel;

            Modal.prototype.render.apply(this, arguments);
        },
        blocks: {
            form_group: function() {
                var Form_group = require('blocks/form/group/group');

                return new Form_group({
                    model: this.models.group
                });
            }
        }
    });
});