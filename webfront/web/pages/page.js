define(function(require) {
    //requirements
    var classExtend = require('kit/_utils/classExtend'),
        $ = require('jquery'),
        _ = require('underscore');

    var $page = $('body');

    var Page = function(){
        $page.data('page', this);
        this.initialize.apply(this, arguments);
    };

    _.extend(Page.prototype, {
        initialize: function(){},
        render: function(){
            var page = this;

            _.each(page.templates, function(template, name){
                var $renderContainer;

                switch (name){
                    case 'content':
                        $renderContainer = $('#content_main');
                        break;
                    default:
                        $renderContainer = $(name);
                        break;
                }

                $renderContainer.children('[block]').each(function() {
                    $(this).data('block').stopListening();
                });

                $renderContainer.html(template({page: page}));
            });
        }
    });

    Page.extend = classExtend;

    return Page;
});