define(function(require, exports, module) {
    //requirements

    if (typeof document === 'undefined') return;
    // Suffix.
    var sfx = 'MatchesSelector';
    var tag = document.createElement('div');
    var name;
    // Detect the right suffix.
    ['matches', 'webkit' + sfx, 'moz' + sfx, 'ms' + sfx].some(function(item) {
        var valid = (item in tag);
        name = item;
        return valid;
    });
    if (!name) throw new Error('Element#matches is not supported');
    return function(element, selector) {
        return element[name](selector);
    };
});