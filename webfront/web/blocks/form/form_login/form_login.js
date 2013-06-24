define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
        _ = require('underscore');

    require('jquery.cookie');

    return Form.extend({
        blockName: 'form_invoice',
        templates: {
            index: require('tpl!./templates/form_login.html')
        },
        submit: function() {
            var block = this,
                deferred = $.Deferred();

            $.ajax({
                url: '/oauth/v2/token',
                method: 'POST',
                data: _.extend(block.data, {
                    grant_type: 'password',
                    client_id: '',
                    client_secret: ''
                }),
                success: function(data){
                    $.cookie('token', data.access_token);
                    document.location.reload();
                },
                error: function(data){
                    deferred.reject(data);
                }
            });

            return deferred.promise();
        }
    });
});