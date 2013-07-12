define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        _ = require('underscore'),
        tokenModel = require('models/token'),
        login = require('utils/login');

    return Form.extend({
        blockName: 'form_invoice',
        templates: {
            index: require('tpl!blocks/form/form_login/templates/index.html')
        },
        submit: function() {
            var block = this,
                deferred = $.Deferred();

            tokenModel.save(block.data, {
                success: function(model) {
                    login(model.get('access_token'));
                },
                error: function(model, res) {
                    deferred.reject({
                        description: res.responseJSON.error_description || res.responseJSON.error
                    })
                }
            });

            return deferred.promise();
        }
    });
});