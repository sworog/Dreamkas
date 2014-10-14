define(function(require, exports, module) {
    //requirements
    var Model = require('kit/model/model');
    var Collection = require('kit/collection/collection');
    var CollectionBlock = require('./collectionBlock');

    var CustomCollection;
    var collection;
    var CustomCollectionBlock;
    var collectionBlock;

    beforeEach(function() {
        CustomCollection = Collection.extend({
            model: Model
        });
        collection = new CustomCollection([ { a: 1 }, { a: 2 } ]);
        CustomCollectionBlock = CollectionBlock.extend({
            collection: collection
        });

        collectionBlock = new CustomCollectionBlock();

        spyOn(collectionBlock, 'render').and.callThrough();
    });

    describe(module.id, function() {

        it('render on push to collection', function() {
            collection.push({ a: 3 });
            expect(collectionBlock.render).toHaveBeenCalled();
        });

        it('render on change item of collection', function() {
            collection.at(0).set({ a: 4 });
            expect(collectionBlock.render).toHaveBeenCalled();
        });

        it('render on remove from collection', function() {
            collection.pop();
            expect(collectionBlock.render).toHaveBeenCalled();
        });

        it('render on reset collection', function() {
            collection.reset([]);
            expect(collectionBlock.render).toHaveBeenCalled();
        });
    });
});