define(function(require, exports, module) {
    //requirements
    var Form = require('kit/form/form.deprecated'),
        GroupModel = require('models/group/group');

    return Form.extend({
        el: '.form_group',
        model: function() {
            return new GroupModel();
        },
        collection: null,
        events: {
            'click .confirmLink__confirmation .group__removeLink': function(e) {
                var block = this;

                e.target.classList.add('loading');

                block.model.destroy().then(function() {
                    e.target.classList.remove('loading');
                });
            }
        },
        initialize: function() {

            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.listenTo(block, 'submit:success', function() {
                if (!block.__model.id) {
                    block.model = new GroupModel();
                }
            });

        }
    });
});