define(function() {
    return Backbone.Model.extend({
        save: function(attributes, options){
            return Backbone.Model.prototype.save.call(this, attributes, _.extend({
                wait: true,
                isSave: true
            }, options));
        },
        toJSON: function(options) {
            options = options || {};

            var toJSON = Backbone.Model.prototype.toJSON;

            if(options.isSave){
                var data = {};
                data[this.modelName] = _.pick(toJSON.apply(this, arguments), this.saveFields);

                return data;
            }

            return toJSON.apply(this, arguments);
        }
    })
});