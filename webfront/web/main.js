require(
    [
        '/routers/mainRouter.js',
        '/helpers/helpers.js'
    ],
    function(router, helpers) {
        $(function() {
            Backbone.history.start({
                pushState: true
            });

            window.app = router;

            window.LH = {
                helpers: helpers   
            };

            $("body").on('click', 'a[href]', function(e) {
                e.preventDefault();
                app.navigate($(this).attr('href'), {trigger: true});
            });
        });
    });