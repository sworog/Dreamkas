define(function(require) {
    //requirements
    var columnModel = require('../models/column');

    return Backbone.Collection.extend({
        model: columnModel
    });
});