define(function(require) {
    //requirements
    var Form = require('kit/form/form');

    require('blocks/select/select_userRole');

    return Form.extend({
        blockName: 'form_user',
        templates: {
            index: require('tpl!./templates/form_user.html')
        }
    });
});