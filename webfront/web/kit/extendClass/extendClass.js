define(function(require) {
        //requirements
        var deepExtend = require('kit/deepExtend/deepExtend');

        require('lodash');

        // Helper function to correctly set up the prototype chain, for subclasses.
        // Similar to `goog.inherits`, but uses a hash of prototype properties and
        // class properties to be extended.
        return function(parent, protoProps, staticProps) {

            if (_.isPlainObject(parent)){
                staticProps = protoProps;
                protoProps = parent;
                parent = this;
            }

            var child,
                instance = true;

            // The constructor function for the new subclass is either defined by you
            // (the "constructor" property in your `extend` definition), or defaulted
            // by us to simply call the parent's constructor.
            if (protoProps && _.has(protoProps, 'constructor')) {
                child = protoProps.constructor;
            } else {
                child = function() {
                    var args;
                    if (this instanceof child){
                        args = instance ? arguments : arguments[0];
                        instance = true;
                        return parent.apply(this, args);
                    } else {
                        instance = false;
                        return new child(arguments);
                    }
                };
            }

            // Add static properties to the constructor function, if supplied.
            deepExtend(child, parent, staticProps);

            child.prototype = Object.create(parent.prototype);

            // Add prototype properties (instance properties) to the subclass,
            // if supplied.
            if (protoProps) deepExtend(child.prototype, protoProps);

            // Set a convenience property in case the parent's prototype is needed
            // later.
            child.__super__ = parent.prototype;

            return child;
        };
    }
);