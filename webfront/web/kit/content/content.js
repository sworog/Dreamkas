define(function(require) {
    //requirements
    var Block = require('kit/block');

    return Block.extend({
        className: 'content',
        blockName: 'content',
        initialize: function(){},
        load: function(templateUrl, data) {
            var block = this;

            block.$el.addClass('preloader preloader_spinner');

            require(['tpl!' + templateUrl], function(template) {
                block.render(template, data);
            })
        },
        render: function(template, data){
            var block = this;

            block.clear();

            block.$el
                .html(template(data))
                .removeClass('preloader preloader_spinner')
                .require();
        },
        clear: function() {
            var block = this;

            block.$el.children('[block]').each(function() {
                $(this).data('block').stopListening();
            });

            block.$el.empty();
        }
    });
});