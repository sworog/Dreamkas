define(function(require, exports, module) {
    //requirements
    var Block = require('kit/block'),
        when = require('when'),
        router = require('router'),
        $ = require('jquery');

    return Block.extend({
        redirectUrl: null,
        elements: {
            $submitButton: function(){
                var block = this;

                return $(block.el).find('[type="submit"]').closest('.button').add('[form="' + block.el.id + '"]');
            },
            $controls: function(){
                var block = this;

                return $(block.el).find('.form__controls');
            }
        },
        events: {
            submit: function(e) {
                e.preventDefault();

                var block = this,
                    submit;

                block.fire('submit:start');
                block.submitStart();

                submit = block.submit();

                submit.done(function(response) {
                    block.submitSuccess(response);
                    block.fire('submit:success', response);
                });

                submit.fail(function(response) {
                    block.submitError(response);
                    block.fire('submit:error', response);
                });

                submit.always(function(response) {
                    block.submitComplete(response);
                    block.fire('submit:complete', response);
                });
            }
        },
        submit: function(){

        },
        submitStart: function() {
            var block = this;

            block.elements.$submitButton.addClass('preloader_stripes');
            block.disable(true);
            block.removeErrors();
            block.removeSuccessMessage();
        },
        submitComplete: function() {
            var block = this;

            block.elements.$submitButton.removeClass('preloader_stripes');
            block.disable(false);
        },
        submitSuccess: function() {
            var block = this;

            if (block.redirectUrl) {
                router.navigate(_.result(block, 'redirectUrl'));
            }

            if (block.successMessage) {
                block.showSuccessMessage();
            }
        },
        submitError: function(response) {
            var block = this;
            block.showErrors(JSON.parse(response.responseText), response);
        },
        showErrors: function(errors, error) {
            var block = this,
                addErrorToInput = function(data, field, prefix) {
                    prefix = prefix || '';

                    var fieldErrors,
                        $input = $(block.el).find('[name="' + prefix + field + '"]'),
                        $field = $input.closest('.form__field');

                    if (data.errors) {
                        $input.addClass('inputText_error');
                        fieldErrors = data.errors.join('. ');
                        $field.attr('data-error', ($field.attr('data-error') ? $field.attr('data-error') + ', ' : '') + fieldErrors);
                    }

                    if (data.children) {
                        var newPrefix = prefix + field + '.';
                        _.each(data.children, function(data, field) {
                            addErrorToInput(data, field, newPrefix);
                        });
                    }
                };

            block.removeErrors();

            if (errors.children) {
                _.each(errors.children, function(data, field) {
                    addErrorToInput(data, field);
                });
            }

            if (errors.error) {
                block.elements.$controls.attr('data-error', typeof errors.error === 'string' ? errors.error : 'неизвестная ошибка: ' + error.statusText);
            }

            if (errors.errors) {
                block.elements.$controls.attr('data-error', errors.errors.join(', '));
            }

            if (errors.description) {
                block.elements.$controls.attr('data-error', errors.description);
            }

            if (errors.error_description) {
                block.elements.$controls.attr('data-error', errors.error_description);
            }
        },
        removeErrors: function() {
            var block = this;

            $(block.el).find('[data-error]').removeAttr('data-error');
            $(block.el).find('.inputText_error').removeClass('inputText_error');
        },
        showSuccessMessage: function() {
            var block = this;

            block.elements.$submitButton.after('<span class="form__successMessage">' + _.result(block, 'successMessage') + '</span>')
        },
        removeSuccessMessage: function() {
            var block = this;

            $(block.el).find('.form__successMessage').remove();
        },
        disable: function(disabled) {
            var block = this;
            if (disabled) {
                block.elements.$submitButton.attr('disabled', true);
            } else {
                block.elements.$submitButton.removeAttr('disabled');
            }
        },
        clear: function() {
            var block = this;

            block.removeErrors();
            block.elements.$submitButton.removeClass('preloader_stripes');

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
    });
});