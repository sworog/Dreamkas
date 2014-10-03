define(function(require, exports, module) {
    //requirements
    var Page = require('./page');

    describe(module.id, function() {

        it('global PAGE variable is right', function() {

            var page = Page({
                el: null
            });

            expect(window.PAGE).toBe(page);
        });

        it('content of #page-wrapper is right', function() {

            var page = Page({
                el: null
            });

            expect($('#page-wrapper', page.el).html().trim()).toBe(page.content());
        });

        it('page fetch', function() {

            spyOn(Page.prototype, 'fetch');

            var page = Page({
                el: null
            });

            expect(Page.prototype.fetch).toHaveBeenCalled();
        });
    });
});