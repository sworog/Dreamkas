define(function(require) {
    //requirements
    var $ = require('jquery'),
        template = require('tpl!./403.html');

    var $page = $('body');

    var Page403 = function(){
        var page = this;

        $page
            .removeAttr('class')
            .addClass('page page_common_403');

        $('#content_main').html(template);
    };

    return Page403;
});