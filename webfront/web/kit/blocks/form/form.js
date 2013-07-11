define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Backbone = require('backbone'),
        _ = require('underscore');

    var router = new Backbone.Router();

    return Block.extend({
        blockName: 'form',
        className: 'form',
        tagName: 'form',
        model: null,
        collection: null,
        redirectUrl: null,
        events: {
            'submit': function(e) {
                e.preventDefault();

                var block = this,
                    data = Backbone.Syphon.serialize(block);

                block.$submitButton.addClass('preloader_rows');

                block.removeErrors();
                block.onSubmit(data);
            }
        },
        findElements: function() {
            var block = this;

            Block.prototype.findElements.apply(block, arguments);

            block.$submitButton = block.$el.find('[type="submit"]').closest('.button').add('input[form="' + block.$el.attr('id') + '"]');
        },
        onSubmit: function(data){
            var block = this;

            block.model.save(data, {
                success: function(model) {
                    if (block.collection){
                        block.collection.push(model);
                    }
                    block.submitSuccess(model);
                },
                error: function(model, response) {
                    block.submitError(JSON.parse(response.responseText));
                }
            });
        },
        submitSuccess: function(data){
            var block = this;

            block.trigger('submit:success', data);

            if (block.redirectUrl){
                router.navigate(block.redirectUrl, {
                    trigger: true
                });
            }

            block.$submitButton.removeClass('preloader_rows');
        },
        submitError: function(data){
            var block = this;
            block.trigger('submit:error', data);
            block.showErrors(data);
            block.$submitButton.removeClass('preloader_rows');
        },
        showErrors: function(errors) {
            var block = this;

            block.removeErrors();
            block.$submitButton.removeClass('preloader preloader_rows');

            if (errors.children) {
                _.each(errors.children, function(data, field) {
                    var fieldErrors;

                    if (data.errors) {
                        fieldErrors = data.errors.join('. ');
                        block.$el.find("[name='" + field + "']").closest(".form__field").attr("lh_field_error", KIT.text(fieldErrors));
                    }
                });
            }

            if (errors.description){
                block.$controls.attr("lh_field_error", KIT.text(errors.description));
            }

        },
        removeErrors: function() {
            var block = this;
            block.$el.find('[lh_field_error]').removeAttr("lh_field_error");
        },
        disable: function(disabled) {
            var block = this;
            if (disabled) {
                block.$el.find('[type=submit]').closest('.button').addClass('button_disabled');
            } else {
                block.$el.find('[type=submit]').closest('.button').removeClass('button_disabled');
            }
        },
        clear: function() {
            var block = this;

            block.removeErrors();
            block.$submitButton.removeClass('preloader preloader_rows');

            block.$el.find(':input').each(function() {
                switch (this.type) {
                    case 'password':
                    case 'select-multiple':
                    case 'select-one':
                    case 'text':
                    case 'textarea':
                    case 'hidden':
                        $(this).val('');
                        break;
                    case 'checkbox':
                    case 'radio':
                        this.checked = false;
                }
            });
        }
    })
});