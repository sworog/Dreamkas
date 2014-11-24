define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal'),
        router = require('router');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        id: 'modal_group',
        deleted: false,
        groupId: null,
        events: {
            'click .modal_group__closeLink': function(e) {

                this.afterDelete();
            }
        },
        models: {
            group: null
        },
        initialize: function(data){

            data = data || {};

            if (typeof data.deleted === 'undefined'){
                this.deleted = false;
            }

            return Modal.prototype.initialize.apply(this, arguments);
        },
        render: function() {
            var GroupModel = require('resources/group/model');

            this.models.group = PAGE.models.group || PAGE.collections.groups.get(this.groupId) || new GroupModel;

            Modal.prototype.render.apply(this, arguments);
        },
        hide: function() {

            if (this.deleted) {
                this.afterDelete();
            }

            return Modal.prototype.hide.apply(this, arguments);
        },
        afterDelete: function() {
            router.navigate('/catalog', { replace: true });
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