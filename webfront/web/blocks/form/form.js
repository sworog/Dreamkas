define(function(require) {
    //requirements
    var Block = require('kit/core/block'),
        setter = require('kit/utils/setter'),
        form2js = require('form2js'),
        translate = require('kit/utils/translate'),
        router = require('router');

    require('lodash');
    require('backbone');

    return Block.extend({
        __name__: 'form',
        className: 'form',
        tagName: 'form',
        model: function() {
            return new Backbone.Model;
        },
        collection: null,
        redirectUrl: null,
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.__model = block.__model || block.model;
            block.model = block.get('__model');

            block.__collection = block.__collection || block.collection;
            block.collection = block.get('__collection');

        },
        events: {
            'change input, checkbox, textarea': function() {
                var block = this;

                block.removeSuccessMessage();
            },
            submit: function(e) {
                e.preventDefault();

                var block = this,
                    formData = form2js(this.el, '.', false),
                    submit;

                block.formData = formData;
                block.trigger('submit:start', formData);
                block.submitStart(formData);

                submit = block.submit(formData);

                submit.done(function(response) {
                    block.submitSuccess(response);
                    block.trigger('submit:success', response);
                });

                submit.fail(function(response) {
                    block.submitError(response);
                    block.trigger('submit:error', response);
                });

                submit.always(function(response) {
                    block.submitComplete(response);
                    block.trigger('submit:complete', response);
                });
            }
        },
        findElements: function() {
            var block = this;

            Block.prototype.findElements.apply(block, arguments);

            block.$submitButton = block.$('[type="submit"]').closest('.button').add('[form="' + block.$el.attr('id') + '"]');
            block.$results = block.$('.form__results');
            block.$controls = block.$submitButton.closest('.form__controls');
        },
        submit: function(formData) {
            var block = this;

            return block.model.save(formData);
        },
        submitStart: function(formData) {
            var block = this;

            block.$submitButton.addClass('preloader_rows');
            block.disable(true);
            block.removeErrors();
            block.removeSuccessMessage();
        },
        submitComplete: function(response) {
            var block = this;

            block.$submitButton.removeClass('preloader_rows');
            block.disable(false);
        },
        submitSuccess: function(response) {
            var block = this;

            if (block.collection) {
                block.collection.push(response);
            }

            if (block.redirectUrl) {
                router.navigate(_.result(block, 'redirectUrl'));
            }

            if (block.successMessage) {
                block.showSuccessMessage();
            }
        },
        submitError: function(response) {
            var block = this;
            block.showErrors(block.parseErrors(response.responseText), response);
        },
        parseErrors: function(data) {
            return JSON.parse(data);
        },
        showErrors: function(errors, error) {
            var block = this;

            block.removeErrors();

            if (errors.children) {
                _.each(errors.children, function(data, field) {
                    var fieldErrors,
                        $input = block.$('[name="' + field + '"]'),
                        $field = $input.closest('.form__field');

                    if (data.errors) {
                        $input.addClass('inputText_error');
                        fieldErrors = data.errors.join('. ');
                        $field.attr('data-error', ($field.attr('data-error') ? $field.attr('data-error') + ', ' : '') + block.translate(fieldErrors));
                    }
                });
            }

            if (errors.error) {
                block.$controls.attr('data-error', typeof errors.error === 'string' ? block.translate(errors.error) : 'неизвестная ошибка: ' + error.statusText);
            }

            if (errors.errors) {
                block.$controls.attr('data-error', errors.errors.join(', '));
            }

            if (errors.description) {
                block.$controls.attr('data-error', block.translate(errors.description));
            }

            if (errors.error_description) {
                block.$controls.attr('data-error', block.translate(errors.error_description));
            }
        },
        removeErrors: function() {
            var block = this;
            block.$el.find('[data-error]').removeAttr('data-error');
            block.$el.find('.inputText_error').removeClass('inputText_error');
        },
        showSuccessMessage: function() {
            var block = this;

            block.$submitButton.after('<span class="form__successMessage">' + block.translate(_.result(block, 'successMessage')) + '</span>')
        },
        removeSuccessMessage: function() {
            var block = this;

            block.$('.form__successMessage').remove();
        },
        disable: function(disabled) {
            var block = this;
            if (disabled) {
                block.$el.find('[type=submit]').closest('.button').attr('disabled', true);
            } else {
                block.$el.find('[type=submit]').closest('.button').removeAttr('disabled');
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