require(
    [
        '/routers/main.js'
    ],
    function(router) {
        $(function() {
            Backbone.history.start({
                pushState: true
            });

            window.app = router;

            $("body").on('click', 'a[href]', function(e) {
                e.preventDefault();
                app.navigate($(this).attr('href'), {trigger: true});
            });
        });
    });