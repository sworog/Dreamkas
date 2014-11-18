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
        id: function() {
            return this.cid;
        },
        data: function() {
            var block = this;

            return block.model && _.cloneDeep(block.model.toJSON());
        },
        events: {
            'focus :input': function() {
                var block = this;

                block.removeSuccessMessage();
            },
            submit: function(e) {
                e.preventDefault();

                var block = this,
                    submitting;

                block.serialize();

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
        globalEvents: {
            'submit:success': function(data, form) {

                var modal = this.$el.closest('.modal')[0],
                    formModal = form.$el.closest('.modal')[0];

                if (formModal && modal && formModal.block.referrer === modal.id) {

                    this.removeErrors();
                }
            }
        },
        initialize: function() {
            var block = this;

            Block.prototype.initialize.apply(block, arguments);

            block.redirectUrl = block.get('redirectUrl');

            block.originalData = block.getData();

            block.__data = block.__data || block.data;

            block.data = block.get('__data');

            //закрытие modal при удалении сущности
            if (block.model) {
                block.listenTo(block.model, {
                    destroy: function () {
                        var modal = block.$el.closest('.modal')[0];

                        if (modal) {
                            modal.block.show({
                                deleted: true
                            });
                        }
                    }
                });
            }
        },
        render: function() {
            var block = this;

            Block.prototype.render.apply(block, arguments);

            block.$submitButton = $(block.el).find('[type="submit"]').add('[form="' + (block.el && block.el.id) + '"]');
        },
        getData: function(){

            var block = this;

            return form2js(block.el, '.', false);
        },
        serialize: function() {
            var block = this;

            block.set('data', block.getData());

            return block.data;
        },
        submit: function() {
            var block = this;

            return block.model.save(block.data);
        },
        submitStart: function() {
            var block = this;

            block.$submitButton = $(block.el).find('[type="submit"]').add('[form="' + (block.el && block.el.id) + '"]');

            block.$submitButton.addClass('loading');
            block.disable();
            block.removeErrors();
            block.removeSuccessMessage();
        },
        submitComplete: function() {
            var block = this;

            block.$submitButton = $(block.el).find('[type="submit"]').add('[form="' + (block.el && block.el.id) + '"]');

            block.$submitButton.removeClass('loading');
            block.enable();
        },
        submitSuccess: function() {
            var block = this,
                modal = block.$el.closest('.modal')[0];

            if (block.collection) {
                block.collection.add(block.model);
            }

            if (modal && !modal.block.referrer) {
                modal.block.hide({ submitSuccess: true });
            }

            if (modal && modal.block.referrer) {
                document.getElementById(modal.block.referrer).block.toggle();
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
                inputElement && inputElement.classList.add('invalid');

                errorMessage = data.errors.map(getText).join('. ');

                if (errorElement){
                    errorElement.classList.add('form__errorMessage_visible');
                    errorElement.innerHTML = getText(errorMessage);
                }
            }

            if (data.children) {
                _.each(data.children, function(data, key) {
                    block.showFieldError(data, field + '.' + key);
                });
            }

        },
        showErrors: function(error) {
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
        showGlobalError: function(errorMessages) {
            var block = this,
                errorMessage,
                $errorElement = block.$('.form__errorMessage_global');

            if ($errorElement.length === 0) {
                $errorElement = $('<div class="form__errorMessage form__errorMessage_global"></div>').prependTo(block.el);
            }

            errorMessage = errorMessages.map(getText).join('. ');

            $errorElement.addClass('form__errorMessage_visible');
            $errorElement.text(getText(errorMessage));

        },
        removeGlobalError: function() {
            var block = this;

            block.$('.form__errorMessage_global').removeClass('form__errorMessage_visible');
        },
        removeErrors: function() {
            var block = this;

            block.$('.form__errorMessage_visible').removeClass('form__errorMessage_visible');
            block.$('input.invalid').removeClass('invalid');
        },
        showSuccessMessage: function() {
            var block = this;

            block.$submitButton.after('<span class="form__successMessage pull-right">' + getText(block.get('successMessage')) + '</span>')
        },
        removeSuccessMessage: function() {
            var block = this;

            block.$el.find('.form__successMessage').remove();
        },
        disable: function() {
            var block = this;

            block.$submitButton.attr('disabled', true);
        },
        enable: function() {
            var block = this;

            block.$submitButton.removeAttr('disabled');
        },
        reset: function() {
            var block = this;

            block.el.reset();

            block.model = block.get('__model');
            block.data = block.get('__data');
        },
        clear: function() {
            var block = this;

            block.$(':input').val('');
        },
        isChanged: function(){

            var block = this,
                originalModel = block.get('__model'),
                originalFormData = block.originalData,
                actualFormData = block.getData(),
                actualData = actualFormData,
                originalData = originalFormData;

            if (block.model && block.model.set){
                block.model.set(actualFormData);
                actualData = block.model.getData()
            }

            if (originalModel && originalModel.getData){
                originalData = originalModel.getData();
            }

            return _.isEqual(originalData, actualData);


        }
    })
});