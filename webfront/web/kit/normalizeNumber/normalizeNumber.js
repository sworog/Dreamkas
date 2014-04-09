define(function(require, exports, module) {
    //requirements
    return function(string) {
        var number = string;

        if (typeof string === 'string'){
            number = string.replace(' ', '', 'gi').replace(',', '.', 'gi');
        }

        return number;
    }
});