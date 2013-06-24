define(function(require) {
    //requirements
    var Form = require('kit/blocks/form/form'),
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
                success: function(data){
                    console.log(data);
                },
                error: function(data){
                    console.log(data);
                }
            });

            return deferred.promise();
        }
    });
});