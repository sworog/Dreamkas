define(function(require) {
    //requirements
    var stringToFragment = require('kit/stringToFragment/stringToFragment'),
        fragmentToString = require('kit/fragmentToString/fragmentToString');

    require('lodash');
    require('jquery');

    return function(template, data) {
        var collection = this,
            elementId = _.uniqueId('collectionNode_');

        var generateElementString = function() {
            var elementString = template(data || {collection: collection}),
                element = stringToFragment(elementString);

            if (element.id){
                elementId = element.id;
            } else {
                element.id = elementId;
            }

            return fragmentToString(element);
        };

        collection.listenTo(collection, 'add remove reset change', function(collection) {
            $('#' + elementId).replaceWith(generateElementString());
        });

        return generateElementString();
    }
});