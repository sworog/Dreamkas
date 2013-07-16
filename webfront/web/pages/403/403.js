define(function(require) {
    //requirements
    var $ = require('jquery'),
        template = require('tpl!./templates/403.html');

    var $page = $('body');

    return function(){
        var page = this;

        $page
            .removeAttr('class')
            .addClass('page page_common_403');

        $('#content').html(template);
    };
});