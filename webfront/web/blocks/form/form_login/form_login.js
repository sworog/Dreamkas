define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        tokenModel = require('models/token'),
        login = require('utils/login');

    return Form.extend({
        blockName: 'form_invoice',
        model: tokenModel,
        templates: {
            index: require('tpl!blocks/form/form_login/templates/index.html')
        },
        onSubmitSuccess: function(model) {
            login(model.get('access_token'));
        }
    });
});