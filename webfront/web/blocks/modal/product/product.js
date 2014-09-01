define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
        template: require('ejs!./template.ejs'),
        models: {
            product: require('models/product/product')
        },
        blocks: {
            form_product: require('blocks/form/product/product')
        }
    });
});