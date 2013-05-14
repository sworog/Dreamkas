define(
    [
        '/kit/form/form.js',
        '/models/invoice.js',
        '/helpers/helpers.js',
        '/routers/mainRouter.js',
        './tpl/tpl.js'
    ],
    function(Form, InvoiceModel, utils, router, tpl) {
        return Form.extend({
            defaults: {
                invoiceId: null
            },
            tpl: tpl,

            initialize: function() {
                var block = this,
                    currentTime = false;

                block.invoiceModel = new InvoiceModel({
                    id: block.invoiceId
                });

                block.listenTo(block.invoiceModel, {
                    sync: function(){
                        block.render();
                    }
                });

                block.render();

                if(!block.invoiceModel.get('acceptanceDate')) {
                    currentTime = true;
                }

                block.datetimepicker("input[name='acceptanceDate']", currentTime);
                block.datepicker("input[name='supplierInvoiceDate']");
            },
            datepicker: function(selector) {
                var jqObj = this.$el.find(selector);

                jqObj.mask('99.99.9999');

                var options = {
                    dateFormat: this.invoiceModel.dateFormat
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
                    dateFormat: this.invoiceModel.dateFormat,
                    timeFormat: this.invoiceModel.timeFormat
                }

                acceptanceDateInput.datetimepicker(options);

                var currentTime = currentTime || false;

                if (currentTime) {
                    acceptanceDateInput.datetimepicker('setDate', new Date())
                }
            }
        });
    }
);