var productRouterE = new ProductsRouter;
var invoicesRouteE = new InvoicesRouter;
var lighthouseRouterE = new LighthouseRouter;

Backbone.history.start({
	pushState: true
});

window.app = new Backbone.Router;

$("body").on('click', 'a', function(event){
    app.navigate($(this).attr('href'), {trigger: true});
    event.preventDefault();
});
