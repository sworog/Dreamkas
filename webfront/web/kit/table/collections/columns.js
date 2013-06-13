define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        ColumnModel = require('kit/table/models/column');

    return Backbone.Collection.extend({
        model: ColumnModel
    });
});