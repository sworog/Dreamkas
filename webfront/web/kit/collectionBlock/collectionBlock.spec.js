define(function(require, exports, module) {
    //requirements
    var CollectionBlock = require('./collectionBlock'),
        Backbone = require('backbone');

    describe(module.id, function(){

        afterEach(function(){
            document.body.innerHTML = '';
        });

        it('call render on collection change', function(){

            var collection = new Backbone.Collection([{
                a: {
                    b: 'b'
                }
            }]);

            var list = new CollectionBlock({
                collection: collection
            });

            spyOn(list, 'render');

            collection.at(0).set('b', 'newB');

            expect(list.render.calls.count()).toEqual(1);
        });

        it('call render on collection add', function(){

            var collection = new Backbone.Collection([]);

            var list = new CollectionBlock({
                collection: collection
            });

            spyOn(list, 'render');

            collection.add({
                a: {
                    b: 'b'
                }
            });

            expect(list.render.calls.count()).toEqual(1);
        });

        it('call render on collection remove', function(){

            var collection = new Backbone.Collection([{
                a: {
                    b: 'b'
                }
            }]);

            var list = new CollectionBlock({
                collection: collection
            });

            spyOn(list, 'render');

            collection.at(0).destroy();

            expect(list.render.calls.count()).toEqual(1);
        });

        it('call render on collection reset', function(){

            var collection = new Backbone.Collection([{
                a: {
                    b: 'b'
                }
            }]);

            var list = new CollectionBlock({
                collection: collection
            });

            spyOn(list, 'render');

            collection.reset([]);

            expect(list.render.calls.count()).toEqual(1);
        });

        it('sortCollection on init', function(){

            var collection = new Backbone.Collection([{
                key: 1
            }, {
                key: 3
            }, {
                key: 4
            }, {
                key: 2
            }]);

            var list = new CollectionBlock({
                sortBy: 'key',
                collection: collection
            });

            expect(list.sortedCollection.toJSON()).toEqual([{
                key: 1
            }, {
                key: 2
            }, {
                key: 3
            }, {
                key: 4
            }]);
        });

        it('sortCollection descending sort direction', function(){

            var collection = new Backbone.Collection([{
                key: 1
            }, {
                key: 3
            }, {
                key: 4
            }, {
                key: 2
            }]);

            var list = new CollectionBlock({
                sortBy: 'key',
                sortDirection: 'descending',
                collection: collection
            });

            expect(list.sortedCollection.toJSON()).toEqual([{
                key: 4
            }, {
                key: 3
            }, {
                key: 2
            }, {
                key: 1
            }]);
        });

        it('sort method', function(){

            var collection = new Backbone.Collection([{
                key: 1
            }, {
                key: 3
            }, {
                key: 4
            }, {
                key: 2
            }]);

            var list = new CollectionBlock({
                collection: collection
            });

            list.sort({
                sortBy: 'key',
                sortDirection: 'descending'
            });

            expect(list.sortedCollection.toJSON()).toEqual([{
                key: 4
            }, {
                key: 3
            }, {
                key: 2
            }, {
                key: 1
            }]);
        });

        it('sort trigger on init', function(){

            var collection = new Backbone.Collection([]);

            var list = new CollectionBlock({
                collection: collection,
                sortBy: 'key',
                template: function(){
                    return '<div><span data-sort-by="key"></span></div>';
                }
            });

            expect(list.$('[data-sort-by="key"]').attr('data-sorted-direction')).toEqual('ascending');
        });

        it('sort trigger on sort', function(){

            var collection = new Backbone.Collection([]);

            var list = new CollectionBlock({
                collection: collection,
                sortBy: 'key',
                template: function(){
                    return '<div><span data-sort-by="key"></span></div>';
                }
            });

            list.sort({
                sortDirection: 'descending'
            });

            expect(list.$('[data-sort-by="key"]').attr('data-sorted-direction')).toEqual('descending');
        });

    });

});