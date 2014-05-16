define(function(require) {
    //requirements

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