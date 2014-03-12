define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        OrderModel = require('models/order'),
        Autocomplete = require('blocks/autocomplete/autocomplete'),
        cookie = require('cookies');

    require('jquery');
    require('lodash');

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/orders',
        el: '.form_order',
        model: function() {
            return new OrderModel();
        },
        initialize: function(){
            var block = this;

            block.blocks = {
                autocomplete: new Autocomplete({
                    el: document.getElementById('autocomplete_storeProduct')
                })
            }
        }
    });
});