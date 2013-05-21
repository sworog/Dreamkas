define(
    [
        '/kit/form/form.js',
        '/models/writeOff.js',
        '/helpers/helpers.js',
        '/routers/mainRouter.js',
        './tpl/tpl.js'
    ],
    function(Form, WriteOffModel, helpers, router, tpl) {
        return Form.extend({
            writeOffFormId: null,
            tpl: tpl,
            writeOffModel: new WriteOffModel(),

            initialize: function() {
                var block = this;

                block.render();

                block.datepicker("input[name='date']", true);
            },
            submit: function(){
                var block = this,
                    deferred = $.Deferred(),
                    formData = Backbone.Syphon.serialize(block);

                block.writeOffModel.save(formData, {
                    success: function(model){
                        router.navigate('/writeOff/' + model.id + '?editMode=true', {
                            trigger: true
                        });
                    },
                    error: function(model, response){
                        deferred.reject(JSON.parse(response.responseText));
                    }
                });

                return deferred.promise();
            },
            datepicker: function(selector, currentTime) {
                var jqObj = this.$el.find(selector);

                jqObj.mask('99.99.9999');

                var options = {
                    dateFormat: this.writeOffModel.dateFormat
                };

                jqObj.datepicker(options);

                if (currentTime) {
                    jqObj.datepicker('setDate', new Date())
                }
            }
        });
    }
);