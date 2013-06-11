define(function(require) {
        //requirements
        var Form = require('kit/form/form'),
            WriteOffModel = require('models/writeOff'),
            helpers = require('helpers/helpers'),
            router = require('routers/mainRouter');

        return Form.extend({
            writeOffFormId: null,
            writeOffModel: new WriteOffModel(),
            className: 'writeOffForm',
            templates: {
                index: require('tpl!./templates/writeOffForm.html')
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
            }
        });
    }
);