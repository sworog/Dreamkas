define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        currentUserModel = require('models/currentUser'),
        InputDate = require('blocks/inputDate/inputDate'),
        router = require('router');

    return Form.extend({
        redirectUrl: function(){
            return '/' + currentUserModel.stores.at(0).id + '/invoices';
        },
        el: '.form_invoice',
        initialize: function(){
            var block = this;

            Form.prototype.initialize.apply(block, arguments);

            new InputDate({
                el: block.el.querySelector('[name="date"]')
            });
        }
    });
});