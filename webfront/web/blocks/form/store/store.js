define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        StoreModel = require('models/store/store');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: function() {
            var StoreModel = require('models/store/store');

            return PAGE.get('collections.stores').get(this.storeId) || new StoreModel;
        },
        collection: function() {
            return PAGE.get('collections.stores');
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