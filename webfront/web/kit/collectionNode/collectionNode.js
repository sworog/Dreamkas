define(function(require) {
    //requirements
    require('lodash');
    require('jquery');

    return function(template, data) {
        var collection = this,
            elementId = _.uniqueId('collectionNode_');

        var generateElementString = function() {
            var element = template(data || {collection: collection}),
                $element = $(element);

            $element.attr('collection', collection.cid);
            $element.attr('id', elementId);

            return $element.wrap('<div></div>').parent().html();
        };

        collection.listenTo(collection, 'add remove reset change', function(collection) {
            $('#' + elementId).replaceWith(generateElementString());
        });

        return generateElementString();
    }
});