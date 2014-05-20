define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        Page = require('kit/page/page');

    require('lodash');
    require('jquery');

    return Form.extend({
        el: '.form_barcode',
        listeners: {
            'submit:success': function(formData){
                Page.current.trigger('form_barcode:submit:success', formData);
            }
        },
        submit: function(formData){
            var deferred = $.Deferred();

            deferred.resolve(formData);

            return deferred.promise();
        }
    });
});