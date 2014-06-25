define(function(require, exports, module) {
    //requirements
    var _ = require('lodash');

    return function(options){
        options = _.extend({
            model: null,

            'read': function(){},
            'create': function(){},
            'update': function(){},
            'delete': function(){}

        }, options);

        return options.model.extend({
            sync: function(method, model, opt){
                var resp = options[method](opt.data);

                resp.error ? opt.error(resp) : opt.success(resp);
            }
        });
    }
});