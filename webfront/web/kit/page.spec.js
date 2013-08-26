define(function(require) {
    //requirements
    var Page = require('kit/page');

    var TestPage = Page.extend({
        __name__: 'page_test',
        string: 'testString',
        a: {
            b: {
                c: 'test'
            }
        }
    });

    var page = new TestPage({}, '/test/:string');

    describe('Lighthouse page', function() {
        it('toUrl method', function() {
            expect(page.route).toEqual('/test/:string');
        });

        it('toUrl method', function() {
            expect(page.toUrl()).toEqual('/test/testString');
        });
    });

});