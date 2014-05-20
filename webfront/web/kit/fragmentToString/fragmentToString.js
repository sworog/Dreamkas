define(function(require, exports, module) {
    //requirements
    require('lodash');

    return function(fragment) {
        var wrapper = document.createElement('div');

        wrapper.appendChild(fragment);

        return wrapper.innerHTML;
    }
});