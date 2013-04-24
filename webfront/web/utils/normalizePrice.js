define(function() {
    return function(value){
        value = +(value.split(',').join('.'));

        return +(value.toFixed(2));
    }
});