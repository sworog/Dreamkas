define(function(require) {
    //requirements
    var classExtend = require('kit/_utils/classExtend'),
        _ = require('underscore');

    var Page = function(){
        this.initialize.apply(this, arguments);
    };

    _.extend(Page.prototype, {
        initialize: function(){}
    });

    Page.extend = classExtend;

    return Page;
});