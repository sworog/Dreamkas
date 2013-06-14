define(function(require) {
    //requirements
    var Backbone = require('backbone');

    return Backbone.Collection.extend({
        model: require('kit/table/models/data')
    });
});