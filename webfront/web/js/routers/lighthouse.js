var LighthouseRouter = Backbone.Router.extend({
    routes: {
        "":                 "dashboard",
        "/":                "dashboard",
        "dashboard":        "dashboard"
    },

    dashboard: function() {
        $("[lh_application]").html(Mustache.render($("#dashboard").html()));
    }
});