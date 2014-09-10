define(function(require, exports, module) {
    //requirements
    var rivets = require('bower_components/rivets/dist/rivets');

    var liveValue = Object.create(rivets.binders.value);

    liveValue.bind = function(el) {
        this.handler = this.handler || this.publish.bind(this);
        el.addEventListener("keyup", this.handler);
        el.addEventListener("change", this.handler);
    };

    liveValue.unbind = function(el) {
        if (this.handler) {
            el.removeEventListener("keyup", this.handler);
            el.removeEventListener("change", this.handler);
        }
    };

    rivets.binders["live-value"] = liveValue;

    return rivets;
});