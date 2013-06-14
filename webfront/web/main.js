require(
    [
        'jquery',
        'backbone',
        'helpers/helpers',
        'routers/mainRouter'
    ],
    function($, Backbone, helpers, router) {
        window.LH = {
            helpers: helpers
        };

        $(function(){
            Backbone.history.start({
                pushState: true
            });

            $('body').on('click', '[href]', function(e) {
                e.preventDefault();
                router.navigate($(this).attr('href'), {trigger: true});
            });
        });
    });