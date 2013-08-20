define(function(require) {
    //requirements
    var _ = require('underscore'),
        Backbone = require('backbone'),
        $ = require('jquery'),
        Page = require('kit/blocks/page/page'),
        deepExtend = require('kit/utils/deepExtend'),
        classExtend = require('kit/utils/classExtend');

    var App = function(options){
        this.initialize.apply(this, arguments);
    };

    _.extend(App.prototype, {
        initialize: function(){}
    });

    App.extend = classExtend();

    return App;
});