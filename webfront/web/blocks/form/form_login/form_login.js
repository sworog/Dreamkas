define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        _ = require('underscore'),
        tokenModel = require('models/token');

    require('jquery.cookie');

    return Form.extend({
        blockName: 'form_invoice',
        templates: {
            index: require('tpl!./templates/form_login.html')
        },
        submit: function() {
            var block = this,
                deferred = $.Deferred();

            tokenModel.save(block.data, {
                success: function(model) {
                    $.cookie('token', model.get('access_token'));
                    document.location.reload();
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