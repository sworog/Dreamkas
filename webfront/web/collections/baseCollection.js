define(function(require) {
    return Backbone.Collection.extend({
        fetch: function(options) {
            return Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true
            }, options));
        },
        sync: function(method, model, options){
            Backbone.Collection.prototype.sync.call(this, method, model, _.extend({
                headers: {
                    Authorization: 'Bearer ' + $.cookie('token')
                }
            }, options));
        }
    });
});