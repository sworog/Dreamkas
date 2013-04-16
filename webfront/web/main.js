require(
    [
        '/routers/main.js',
        '/views/Block.js',
        '/collections/base.js',
        '/models/base.js'
    ],
    function(router) {
        $(function() {
            Backbone.history.start({
                pushState: true
            });

            $("body").on('click', 'a', function(e) {
                e.preventDefault();
                router.navigate($(this).attr('href'), {trigger: true});
            });
        });
    });