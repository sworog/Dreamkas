define(function(require) {
    //requirements
    var columnModel = require('kit/blocks/table/models/column');

    return Backbone.Collection.extend({
        model: columnModel
    });
});