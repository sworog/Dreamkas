define(function(require) {
    //requirements
    var Form = require('kit/form/form'),
        UserModel = require('models/user');

    require('blocks/select/_userRole');

    return Form.extend({
        blockName: 'form_user',
        model: UserModel,
        modelId: null,
        templates: {
            index: require('tpl!./templates/form_user.html')
        }
    });
});