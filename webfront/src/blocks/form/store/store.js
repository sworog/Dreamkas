define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form');

    return Form.extend({
        template: require('ejs!./template.ejs'),
        model: function() {
            var StoreModel = require('resources/store/model');

            return PAGE.get('collections.stores').get(this.storeId) || new StoreModel;
        },
        collection: function() {
            return PAGE.get('collections.stores');
        }
    });
});