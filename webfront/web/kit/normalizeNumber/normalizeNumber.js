define(function(require, exports, module) {
    //requirements
    return function(string) {
        return string.toString().replace(' ', '', 'gi').replace(',', '.', 'gi');
    }
});