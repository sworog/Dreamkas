define(function(require, exports, module) {
    //requirements
    var $ = require('jquery');

    $(document)
        .on('click', '.confirmLink__trigger', function(e) {
            e.stopPropagation();

            var confirmLink = $(this).closest('.confirmLink');

            confirmLink.addClass('confirmLink_active');
        })
        .on('click', function(e) {
            var confirmLink_active = $('.confirmLink_active').not($(e.target).closest('.confirmLink_active'));

            confirmLink_active.removeClass('confirmLink_active');
        })
});