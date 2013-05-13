define(
    [
        '/kit/block.js'
    ],
    function(Block) {
    return Block.extend({
        initialize: function(){
            var block = this;
            block.$submitButton = block.$el.find('[type="submit"]').closest('.button');
        },
        events: {
            'submit': function(e){
                e.preventDefault();
                var block = this;

                block.$submitButton.addClass('preloader');
                block.removeErrors();

                block.submit().then(function(data){
                    block.trigger('successSubmit', data);
                    block.$submitButton.removeClass('preloader');
                }, function(data){
                    block.showErrors(data);
                    block.$submitButton.removeClass('preloader');
                });
            }
        },
        render: function(){
            var block = this;
            Block.prototype.render.apply(block, arguments);

            block.$submitButton = block.$el.find('[type="submit"]').closest('.button');
        },
        showErrors: function(data) {
            var block = this;

            block.removeErrors();
            block.$submitButton.removeClass('preloader');

            _.each(data.children, function(data, field) {
                var fieldErrors;

                if (data.errors){
                    fieldErrors = data.errors.join(', ');
                }

                block.$el.find("[name='" + field + "']").closest(".form__field").attr("lh_field_error", fieldErrors);
            });
        },
        removeErrors: function(){
            var block = this;
            block.$el.find("input, textarea, select").closest(".form__field").removeAttr("lh_field_error");
        },
        disable: function(disabled){
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
            block.$submitButton.removeClass('preloader');

            block.$el.find(':input').each(function() {
                switch(this.type) {
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