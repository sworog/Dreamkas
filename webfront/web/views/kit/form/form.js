define(
    [
        '/views/kit/main.js'
    ],
    function(Block) {
    return Block.extend({
        initialize: function(){
            var block = this;
        },
        showErrors: function(data) {
            var block = this;

            this.removeErrors();

            _.each(data.children, function(data, field) {
                var fieldErrors;

                if (data.errors){
                    fieldErrors = data.errors.join(', ');
                }

                block.$el.find("[name='" + field + "']").closest(".form__field").attr("lh_field_error", fieldErrors);
            });
        },
        removeErrors: function(){
            this.$el.find("input, textarea, select").closest(".form__field").removeAttr("lh_field_error");
        },
        clear: function() {
            this.removeErrors();

            this.$el.find(':input').each(function() {
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