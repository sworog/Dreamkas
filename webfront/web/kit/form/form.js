define(function(require) {
    //requirements
    var Block = require('kit/block'),
        Backbone = require('backbone');

    require('backbone.syphon');

    return Block.extend({
        tagName: 'form',
        className: 'form',
        Model: Backbone.Model,
        model: null,
        events: {
            'submit': function(e) {
                e.preventDefault();
                var block = this;

                block.$submitButton.addClass('preloader preloader_rows');
                block.removeErrors();

                block.submit().then(function(data) {
                    block.trigger('successSubmit', data);
                    block.$submitButton.removeClass('preloader preloader_rows');
                }, function(data) {
                    block.showErrors(data);
                    block.$submitButton.removeClass('preloader preloader_rows');
                });
            }
        },
        listeners: {
            model: {
                change: function(){
                    var block = this;

                    block.render();
                }
            }
        },
        fetch: function(){
            var block = this;

            if (block.model.id){
                block.model.fetch();
            }
        },
        findElements: function() {
            var block = this;

            Block.prototype.findElements.apply(block, arguments);

            block.$submitButton = block.$el.find('[type="submit"]').closest('.button').add('input[form="' + block.$el.attr('id') + '"]');
        },
        submit: function() {
            var block = this,
                deferred = $.Deferred(),
                formData = Backbone.Syphon.serialize(block),
                model = block.model.id ? block.model : block.model.clone();

            model.save(formData, {
                success: function(model) {
                    deferred.resolve(model);
                },
                error: function(model, response) {
                    deferred.reject(JSON.parse(response.responseText));
                }
            });

            return deferred.promise();
        },
        showErrors: function(errors) {
            var block = this;

            block.removeErrors();
            block.$submitButton.removeClass('preloader preloader_rows');

            if (errors) {
                _.each(errors.children, function(data, field) {
                    var fieldErrors;

                    if (data.errors) {
                        fieldErrors = data.errors.join(', ');
                    }

                    block.$el.find("[name='" + field + "']").closest(".form__field").attr("lh_field_error", fieldErrors);
                });
            }

        },
        removeErrors: function() {
            var block = this;
            block.$el.find("input, textarea, select").closest(".form__field").removeAttr("lh_field_error");
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
    });
});