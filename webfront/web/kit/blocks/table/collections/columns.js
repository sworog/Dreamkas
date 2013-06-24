define(function(require) {
    //requirements
    var Backbone = require('backbone'),
        _ = require('underscore'),
        columnModel = require('kit/blocks/table/models/column');

    return Backbone.Collection.extend({
        model: columnModel
    });
});