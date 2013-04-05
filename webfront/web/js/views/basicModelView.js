var BasicModelView = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    initialize: function() {
        _.bindAll(this, 'render');

        this.model.bind('all', this.render, this);
    },

    render: function() {
        this.$el.html(this.template(this.model.toJSON()));

        return this;
    }
});