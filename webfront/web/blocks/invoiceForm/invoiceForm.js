define(
    [
        '/kit/form/form.js',
        '/models/invoice.js',
        '/routers/mainRouter.js',
        './tpl/tpl.js'
    ],
    function(Form, InvoiceModel, router, tpl) {
        return Form.extend({
            defaults: {
                invoiceId: null,
                tpl: tpl
            },

            initialize: function() {
                var block = this;

                block.invoiceModel = new InvoiceModel({
                    id: block.invoiceId
                });

                block.listenTo(block.invoiceModel, {
                    sync: function(){
                        block.render();
                    }
                });

                block.render();
            },
            submit: function(){
                var block = this,
                    deferred = $.Deferred(),
                    formData = Backbone.Syphon.serialize(block);

                block.invoiceModel.save(formData, {
                    success: function(model){
                        router.navigate('/invoice/' + model.id + '?editMode=true', {
                            trigger: true
                        });
                    },
                    error: function(model, response){
                        deferred.reject(JSON.parse(response.responseText));
                    }
                });

                return deferred.promise();
            }
        });
    }
);