define(function() {
    return function(obj){
        if (obj === null){
            return 'null';
        }

        if (obj === undefined){
            return 'undefined';
        }

        if (obj === false || obj === true){
            return 'boolean';
        }

        return Object.prototype.toString.call(obj).split(' ')[1].split(']')[0].toLowerCase();
    }
});