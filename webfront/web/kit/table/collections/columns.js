define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        _ = require('underscore'),
        columnModel = require('kit/table/models/column');

    return Backbone.Collection.extend({
        model: columnModel
    });
});