define(function(require) {
    //requirements
    var Backbone = require('backbone');

    return Backbone.Model.extend({
        initialize: function(attr, opt){
            if (typeof attr === 'string'){
                this.set({
                    label: attr
                })
            }
        }
    });
});