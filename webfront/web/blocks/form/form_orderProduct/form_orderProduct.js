define(function(require, exports, module) {
    //requirements
    var Form = require('blocks/form/form'),
        OrderModel = require('models/order'),
        cookie = require('cookies');

    require('jquery');
    require('lodash');

    var authorizationHeader = 'Bearer ' + cookie.get('token');

    return Form.extend({
        __name__: module.id,
        redirectUrl: '/orders',
        el: '.form_orders',
        model: function() {
            return new OrderModel();
        }
    });
});