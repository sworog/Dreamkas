define(function(require) {
    //requirements
    var Page = require('./page');

    var TestPage = Page.extend({
        __name__: 'page_test',
        string: 'test',
        a: {
            b: {
                c: 'test'
            }
        }
    });

    var page = new TestPage({}, '/test/:string');

    describe('Lighthouse page', function() {
        it('save method', function() {
            expect(typeof page.save).toEqual('function');
        });
    });

});