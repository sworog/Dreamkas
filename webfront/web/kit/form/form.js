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
                    submit;

                block.formData = block.getData();

                block.submitStart();
                block.trigger('submit:start');

                Promise.resolve(block.submit()).then(function() {

                    block.submitSuccess();
                    block.trigger('submit:success');

                    block.submitComplete();
                    block.trigger('submit:complete');

                }, function(response) {

                    block.submitError(response);
                    block.trigger('submit:error', response);

                    block.submitComplete(response);
                    block.trigger('submit:complete', response);

                });
            }
        },
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.$submitButton = $(block.el).find('[type="submit"]').add('[form="' + block.el.id + '"]');
            block.model = block.get('model');
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

            block.disable();
            block.removeErrors();
            block.removeSuccessMessage();
        },
        submitComplete: function() {
            var block = this;

            block.enable();
        },
        submitSuccess: function() {
            var block = this;

            if (block.collection) {
                block.collection.push(block.model);
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
                errorElement = block.el.querySelector('.form__errorMessage[for="' + field + '"]');

            if (data.errors) {
                inputElement.classList.add('error');

                errorMessage = data.errors.map(getText).join('. ');

                errorElement.classList.add('form__errorMessage_visible');
                errorElement.innerHTML = getText(errorMessage);
            }

        },
        showErrors: function(errors, response) {
            var block = this;

            block.removeErrors();

            if (errors.children) {
                _.each(errors.children, function(data, field) {
                    block.showFieldError(data, field);
                });
            }
        },
        removeErrors: function() {
            var block = this;

            block.$('.form__errorMessage_visible').removeClass('form__errorMessage_visible');
            block.$('input.error').removeClass('error');
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
        }
    })
});