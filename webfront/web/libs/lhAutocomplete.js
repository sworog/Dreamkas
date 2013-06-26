(function($) {
    $.extend(true, $.ui.autocomplete.prototype, {
        getTerm: function() {
            if (undefined != this.term) {
                return this.term;
            } else {
                return null;
            }
        }
    });
}(jQuery));