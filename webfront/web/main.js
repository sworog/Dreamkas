require(
    [
        '/routers/main.js',
        '/views/Block.js',
        '/collections/base.js',
        '/models/base.js'
    ],
    function() {
        $(function() {
            Backbone.history.start({
                pushState: true
            });

            window.app = new Backbone.Router();

            $("body").on('click', 'a[href]', function(e) {
                e.preventDefault();
                app.navigate($(this).attr('href'), {trigger: true});
            });
        });
    });