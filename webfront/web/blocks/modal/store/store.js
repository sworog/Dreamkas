define(function(require, exports, module) {
    //requirements
    var Modal = require('blocks/modal/modal');

    return Modal.extend({
		storeId: 0,
        template: require('ejs!./template.ejs'),
        blocks: {
            storeForm: require('blocks/form/store/store')
        }
    });
});