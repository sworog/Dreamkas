define(
    [
        '/kit/form/form.js',
        '/models/writeOff.js',
        '/helpers/helpers.js',
        '/routers/mainRouter.js',
        './writeOffForm.templates.js'
    ],
    function(Form, WriteOffModel, helpers, router, templates) {
        return Form.extend({
            writeOffFormId: null,
            writeOffModel: new WriteOffModel(),
            className: 'writeOffForm',
            templates: templates,

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
            }
        });
    }
);