define(function(require, exports, module) {
    //requirements
    var Backbone = require('backbone'),
        makeClass = require('kit/makeClass/makeClass'),
        deepExtend = require('kit/deepExtend/deepExtend'),
        get = require('kit/get/get'),
        _ = require('lodash');

    return makeClass(function(params) {

        var resource = this;

        deepExtend(resource, params);

    }, _.extend({
        fetch: function(opt) {

            var resource = this;

            opt = deepExtend({
                url: get(this, 'url'),
                data: get(this, 'params')
            }, opt);

            resource.request = $.ajax(opt);

            return resource.request.then(function(data){
                resource.data = data;
                resource.trigger('fetched', data);
            });

        },
        get: function(path) {
            return get(this, 'data.' + path);
        }
    }, Backbone.Events));
});