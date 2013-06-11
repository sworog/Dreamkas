define(function(require) {
        //requirements
        var Form = require('kit/form/form'),
            moment = require('moment'),
            InvoiceModel = require('models/invoice'),
            router = require('routers/mainRouter');

        return Form.extend({
            invoiceId: null,
            className: 'invoiceForm',
            templates: {
                index: require('tpl!./templates/invoiceForm.html')
            },

            initialize: function() {
                var block = this;

                block.invoiceModel = new InvoiceModel({
                    id: block.invoiceId
                });

                block.listenTo(block.invoiceModel, {
                    sync: function() {
                        block.render();
                    }
                });

                block.render();
            },
            submit: function() {
                var block = this,
                    deferred = $.Deferred(),
                    formData = Backbone.Syphon.serialize(block);

                block.invoiceModel.save(formData, {
                    success: function(model) {
                        router.navigate('/invoice/' + model.id + '?editMode=true', {
                            trigger: true
                        });
                    },
                    error: function(model, response) {
                        deferred.reject(JSON.parse(response.responseText));
                    }
                });

                return deferred.promise();
            }
        });
    }
);