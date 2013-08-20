define(function() {
    return function(value){
        if (value.length === 0){
            return null;
        }
        value = +(value.split(',').join('.'));

        return +(value.toFixed(2));
    }
});