define(function(require, exports, module) {
    //requirements
    var config = require('config'),
        _ = require('lodash'),
        $ = require('jquery');

    require('backbone');

    var Collection = Backbone.Collection.extend({
        initialize: function(data, options){
            _.extend(this, options);
        },
        fetch: function(options) {
            return Backbone.Collection.prototype.fetch.call(this, _.extend({
                reset: true
            }, options));
        },
        element: function(template, data) {
            var collection = this,
                elementId = _.uniqueId('collectionElement');

            var generateElementString = function() {
                var wrapper = document.createElement('div'),
                    element;

                wrapper.innerHTML = template(data || {collection: collection});

                element = wrapper.children[0];

                if (element.id) {
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

    Collection.baseApiUrl = config.baseApiUrl;
    Collection.mockApiUrl = config.mockApiUrl;


    return Collection;
});