define(function(require) {
    //requirements
    var Form = require('blocks/form/form'),
        Backbone = require('backbone');

    var router = new Backbone.Router();

    return Form.extend({
        blockName: 'form_writeOff',
        templates: {
            index: require('tpl!./templates/form_writeOff.html')
        },
        submit: function() {
            var block = this,
                deferred = $.Deferred(),
                formData = Backbone.Syphon.serialize(block);

            block.model.save(formData, {
                success: function(model) {
                    router.navigate('/writeOffs/' + model.id + '?editMode=true', {
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
});