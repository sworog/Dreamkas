define(function(require, exports, module) {
    //requirements
    return function(number) {
        return parseFloat((number || '').toString()
            .replace(' ', '', 'gi')
            .replace(',', '.', 'gi'));
    }
});