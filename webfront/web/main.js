require(
    [
        '/helpers/helpers.js'
    ],
    function(helpers) {
        $(function() {
            Backbone.history.start({
                pushState: true
            });

            moment.lang('ru');

            window.LH = {
                helpers: helpers   
            };
        });
    });