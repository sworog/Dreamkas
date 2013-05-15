require(
    [
        '/helpers/helpers.js',
        '/routers/mainRouter.js'
    ],
    function(helpers, router) {
        $(function() {
            Backbone.history.start({
                pushState: true
            });

            window.LH = {
                helpers: helpers   
            };

            $('body').on('click', '[href]', function(e) {
                e.preventDefault();
                router.navigate($(this).attr('href'), {trigger: true});
            });
        });
    });