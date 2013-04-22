define(
    [
        '/views/kit/main.js'
    ],
    function(Block) {
    return Block.extend({
        initialize: function(){
            var block = this;
        },
        showErrors: function(errors){
            var block = this;
        },
        removeErrors: function(){
            var block = this;
            block.$el.find("input, textarea, select").closest(".form__field").removeAttr("lh_field_error");
        },
        clear: function() {
            var block = this;

            block.removeErrors();

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