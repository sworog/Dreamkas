require(
    [
        'jquery',
        'backbone',
        'underscore',
        'router',
        'utils',
        'nls'
    ],
    function($, Backbone, _, router) {
        $(function() {

            Backbone.history.start({
                pushState: true
            });

            $('body').on('click', '[href]', function(e) {
                e.preventDefault();
                router.navigate($(this).attr('href'), {
                    trigger: true
                });
            });
        });
    });