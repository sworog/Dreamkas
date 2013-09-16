define(function(require) {
    //requirements

    return Backbone.Collection.extend({
        model: require('../models/data.js')
    });
});