define(function(require) {
    //requirements
    var columnModel = require('../models/column.js');

    return Backbone.Collection.extend({
        model: columnModel
    });
});