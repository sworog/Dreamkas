define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: function(){
            var GroupModel = require('models/group/group');

            return PAGE.get('models.group') || new GroupModel;
        },
        collection: function(){
            return PAGE.get('collections.groups');
        },
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

            var isNew = block.model.isNew();

            block.listenTo(block, 'submit:success', function() {
                if (isNew) {
                    block.reset();
                }
            });

        }
    });
});