define(function(require) {
    //requirements

    require('lodash');
    require('jquery');

    return function(template, data) {
        var collection = this,
            elementId = _.uniqueId('collectionNode_');

        var generateElementString = function() {
            var wrapper = document.createElement('div'),
                element;

            wrapper.innerHTML = template(data || {collection: collection});

            element = wrapper.children[0];

            if (element.id){
                elementId = element.id;
            } else {
                element.id = elementId;
            }

            return wrapper.innerHTML;
        };

        var elementString = generateElementString();

        collection.listenTo(collection, 'add remove reset change', function(collection) {
            $('#' + elementId).replaceWith(generateElementString());
        });

        return elementString;
    }
});