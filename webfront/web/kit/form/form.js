define(function(require) {
    //requirements
    var Block = require('kit/block/block'),
        getText = require('kit/getText/getText'),
        form2js = require('form2js'),
        router = require('router'),
        _ = require('lodash');

    return Block.extend({
        model: null,
        collection: null,
        redirectUrl: null,
        events: {
            'change input, checkbox, textarea': function() {
                var block = this;

                block.removeSuccessMessage();
            },
            submit: function(e) {
                e.preventDefault();

                var block = this,
                    submitting;

                block.formData = block.getData();

                submitting = block.submit();

                block.submitStart();
                block.trigger('submit:start');

                submitting.done(function(response) {
                    block.submitSuccess(response);
                    block.trigger('submit:success', response);
                });

                submitting.fail(function(response) {
                    block.submitError(response);
                    block.trigger('submit:error', response);
                });

                submitting.always(function(response) {
                    block.submitComplete(response);
                    block.trigger('submit:complete', response);
                });
            }
        },
        initialize: function() {
            var block = this;

            block.__model = block.model = block.get('model');
            block.__collection = block.collection = block.get('collection');

            Block.prototype.initialize.apply(block, arguments);

            block.$el.closest('.modal').on('hidden.bs.modal', function() {
                block.reset();
            });

            block.$submitButton = $(block.el).find('[type="submit"]').add('[form="' +  (block.el && block.el.id) + '"]');

            block.redirectUrl = block.get('redirectUrl');
        },
        getData: function() {
            return form2js(this.el, '.', false);
        },
        submit: function() {
            var block = this;

            return block.model.save(block.formData);
        },
        submitStart: function() {
            var block = this;

            block.$submitButton.addClass('loading');
            block.disable();
            block.removeErrors();
            block.removeSuccessMessage();
        },
        submitComplete: function() {
            var block = this;

            block.$submitButton.removeClass('loading');
            block.enable();
        },
        submitSuccess: function() {
            var block = this;

            if (block.collection) {
                block.collection.add(block.model);
            }

            if (block.redirectUrl) {
                router.navigate(block.redirectUrl);
                return;
            }

            if (block.get('successMessage')) {
                block.showSuccessMessage();
            }
        },
        submitError: function(response) {
            var block = this;

            block.showErrors(JSON.parse(response.responseText), response);
        },
        showFieldError: function(data, field) {
            var block = this,
                errorMessage,
                inputElement = block.el.querySelector('[name="' + field + '"]'),
                errorElement = block.el.querySelector('.form__errorMessage[for="' + field + '"]') || $('<div for="' + field + '" class="form__errorMessage"></div>').insertAfter(inputElement)[0];

            if (data.errors && data.errors.length) {
                inputElement.classList.add('invalid');

                errorMessage = data.errors.map(getText).join('. ');

                errorElement.classList.add('form__errorMessage_visible');
                errorElement.innerHTML = getText(errorMessage);
            }

        },
        showErrors: function(error, response) {
            var block = this;

            block.removeErrors();

            if (error.errors.children) {
                _.each(error.errors.children, function(data, field) {
                    block.showFieldError(data, field);
                });
            }

            if (error.errors.errors && error.errors.errors.length) {
                block.showGlobalError(error.errors.errors);
            }
        },
        showGlobalError: function(errorMessages){
            var block = this,
                errorMessage,
                $errorElement = block.$('.form__errorMessage_global');

            if ($errorElement.length === 0){
                $errorElement = $('<div class="form__errorMessage form__errorMessage_global"></div>').prependTo(block.el);
            }

            errorMessage = errorMessages.map(getText).join('. ');

            $errorElement.addClass('form__errorMessage_visible');
            $errorElement.text(getText(errorMessage));

        },
        removeErrors: function() {
            var block = this;

            block.$('.form__errorMessage_visible').removeClass('form__errorMessage_visible');
            block.$('input.invalid').removeClass('invalid');
        },
        showSuccessMessage: function() {
            var block = this;

            //block.elements.$submitButton.after('<span class="form__successMessage">' + getText(block.get('successMessage')) + '</span>')
        },
        removeSuccessMessage: function() {
            var block = this;

            //$(block.el).find('.form__successMessage').remove();
        },
        disable: function() {
            var block = this;

            block.$submitButton.attr('disabled', true);
        },
        enable: function() {
            var block = this;

            block.$submitButton.removeAttr('disabled');
        },
        reset: function(){
            PAGE.render();
        },
        clear: function(){
            var block = this;

            block.$('input').val('');
        }
    })
});