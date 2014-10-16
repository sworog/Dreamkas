define(function(require, exports, module) {

    return {
        reload: function() {
            document.location.reload();
        },
        redirect: function(href) {
            document.location.href = href;
        },
        isContain: function(string) {
            return document.location.pathname.indexOf(string) >= 0;
        }
    };
});