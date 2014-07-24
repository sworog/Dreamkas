define(function(require, exports, module) {
    //requirements
    return function(string) {
        return parseFloat((string || '').toString()
            .replace(' ', '', 'gi')
            .replace(',', '.', 'gi'));
    }
});