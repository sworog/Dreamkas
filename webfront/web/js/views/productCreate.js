var ProductCreate = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    template: Mustache.compile($("#productEdit").html()),

    events: {
        'submit form': "formSubmitted"
    },

    initialize: function() {
        this.model.bind('error', this.render, this);
    },

    render: function() {
        var data = this.model.toJSON();
        data.errors = this.model.errors;
        var unitsSelect = [];
        for(var unitId in this.model.unitsEnum) {
            var unitItem = {}
            unitItem = this.model.unitsEnum[unitId];
            unitItem.value = unitId;
            if(data.units == unitId) {
                unitItem.selected = true;
            }
            unitsSelect.push(unitItem);
        }

        data.unitsSelect = unitsSelect;

        var vatSelect = [];
        for(var vatValue in this.model.vatEnum) {
            var vatItem = {};
            vatItem.value = this.model.vatEnum[vatValue];
            if(data.vat == vatItem.value) {
                vatItem.selected = true;
            }
            vatSelect.push(vatItem);
        }

        data.vatSelect = vatSelect;

        this.$el.html(this.template(data));

        return this;
    },

    formSubmitted: function(event) {
        event.preventDefault();

        this.model.errors = {};
        var isNew = this.model.isNew();

        var data = Backbone.Syphon.serialize(this);
        this.model.set(data);

        this.model.save({}, {
            error: function(model, response) {
                model.parseErrors($.parseJSON(response.responseText));
            },
            success: function(model, response) {
                if(isNew) {
                    app.navigate('products', {trigger: true});
                } else {
                    app.navigate('product_view/' + model.get('id'), {trigger: true});
                }
            }
        });
    }
});