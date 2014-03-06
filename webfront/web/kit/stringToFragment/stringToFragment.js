define(function(require, exports, module) {
    //requirements
    require('lodash');

    return function(string) {
        var wrapper = document.createElement('div'),
            fragment = document.createDocumentFragment();

        wrapper.innerHTML = string;

        _.forEach(_.toArray(wrapper.childNodes), function(node) {
            fragment.appendChild(node);
        });

        return fragment;
    }
});