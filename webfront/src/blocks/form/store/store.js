define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        id: 'form_store',
        events: {
            'click .form_store__removeLink': function(e) {
                var block = this;

                e.target.classList.add('loading');

                block.model.destroy().then(function() {
                    e.target.classList.remove('loading');
                }, function(data) {
                    block.showError(data.responseJSON.message, 'delete');
                    e.target.classList.remove('loading');
                });
            }
        },
        model: function() {
            var StoreModel = require('resources/store/model');

            return PAGE.get('collections.stores').get(this.storeId) || new StoreModel;
        },
        collection: function() {
            return PAGE.get('collections.stores');
        }
    });
});