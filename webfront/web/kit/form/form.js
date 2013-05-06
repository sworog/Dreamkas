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
                var block = this;
                e.preventDefault();
                block.$submitButton.addClass('preloader');
                block.removeErrors();
            }
        },
        render: function(){
            Block.prototype.render.apply(this, arguments);

            var block = this;
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