var InvoiceCreateView = Backbone.View.extend({
    tagName: "div",
    attributes: {
        lh_card_stack: "true"
    },

    template: Mustache.compile($("#invoiceEdit").html()),

    events: {
        'submit form': "formSubmitted"
    },

    initialize: function() {
        this.model.bind('error', this.render, this);
    },

    render: function() {
        var currentTime = false;
        var data = this.model.toJSON();
        data.errors = this.model.errors;

        if(data.acceptanceDate == null) {
            currentTime = true;
        }

        this.$el.html(this.template(data));

        this.datetimepicker("input[name='acceptanceDate']", currentTime);
        this.datepicker("input[name='supplierInvoiceDate']");

        return this;
    },

    formSubmitted: function(event) {
        event.preventDefault();

        this.model.errors = {};

        var data = Backbone.Syphon.serialize(this);
        this.model.set(data);

        this.model.save({}, {
            error: function(model, response) {
                model.parseErrors($.parseJSON(response.responseText));
            },
            success: function(model, response) {
                app.navigate('invoice/list', {trigger: true});
            }
        });
    },

    datepicker: function(selector) {
        var jqObj = this.$el.find(selector);

        jqObj.mask('99.99.9999');

        var options = {
            dateFormat: this.model.dateFormat
        }

        jqObj.datepicker(options);

        var currentTime = currentTime || false;

        if (currentTime) {
            jqObj.datepicker('setDate', new Date())
        }
    },

    datetimepicker: function(selector, currentTime) {
        var acceptanceDateInput = this.$el.find(selector);

        acceptanceDateInput.mask('99.99.9999 99:99');

        var onClose= function(dateText, datepicker) {
            acceptanceDateInput.val(dateText);
        }

        var dateTimePickerControl = {
            create: function(tp_inst, obj, unit, val, min, max, step){
                var input = jQuery('<input class="ui-timepicker-input" value="'+val+'" style="width:50%">');
                input.change(function(e){
                    if (e.originalEvent !== undefined) {
                        tp_inst._onTimeChange();
                    }
                    tp_inst._onSelectHandler();
                })
                input.appendTo(obj);
                return obj;
            },
            options: function(tp_inst, obj, unit, opts, val){
            },
            value: function(tp_inst, obj, unit, val){
                if (val !== undefined) {
                    return obj.find('.ui-timepicker-input').val(val);
                } else {
                    return obj.find('.ui-timepicker-input').val();
                }
            }
        };

        var options = {
            controlType: dateTimePickerControl,
            onClose: onClose,
            dateFormat: this.model.dateFormat,
            timeFormat: this.model.timeFormat
        }

        acceptanceDateInput.datetimepicker(options);

        var currentTime = currentTime || false;

        if (currentTime) {
            acceptanceDateInput.datetimepicker('setDate', new Date())
        }
    }
});