define(function(require) {
    //requirements

    return Backbone.Collection.extend({
        model: require('kit/blocks/table/models/data')
    });
});