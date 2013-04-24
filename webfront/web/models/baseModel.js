define(function() {
    return Backbone.Model.extend({
        save: function(attributes, options){
            return Backbone.Model.prototype.save.call(this, attributes, _.extend({
                isSave: true,
                wait: true
            }, options));
        },
        toJSON: function(options) {
            options = options || {};
            var toJSON = Backbone.Model.prototype.toJSON;

            if(options.isSave){
                var data = {};
                data[this.modelName] = _.pick(toJSON.apply(this, arguments), _.keys(this.defaults));

                return data;
            }

            return toJSON.apply(this, arguments);
        }
    })
});