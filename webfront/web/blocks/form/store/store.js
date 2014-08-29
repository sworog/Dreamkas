define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form.deprecated'),
        StoreModel = require('models/store/store');

    return Form.extend({
        el: '.form_store',
        model: function() {
            return new StoreModel();
        },
        collection: null,
        initialize: function() {

            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            block.listenTo(block, 'submit:success', function() {
                if (!block.__model.id) {
                    block.model = new StoreModel();
                }
            });

        }
    });
});