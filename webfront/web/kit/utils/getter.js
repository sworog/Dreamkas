define(function(require) {
    //requirements
    var _ = require('underscore');

    /**
     * Примесь для добавления к объекту get-методов
     *
     * @class getter
     * @static
     */
    return {
        /**
         * Get-метод
         *
         *     var c = object.get('a.b.c');
         *
         * @method get
         */
        get: function(path){
            var object = this,
                attr = this,
                segments = path.split('.');

            _.every(segments, function(segment){

                if (_.isFunction(attr[segment])){
                    attr = attr[segment].call(object)
                } else {
                    attr = attr[segment];
                }

                return attr;
            });

            return attr;
        }
    }
});